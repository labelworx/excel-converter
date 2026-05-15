<?php

namespace LabelWorx\ExcelConverter\Converters;

use LabelWorx\ExcelConverter\Exceptions\ExcelConverterException;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class AbstractConvertFromExcel extends BaseConverter
{
    abstract protected function createReader(): IReader;

    public function convert(): void
    {
        $reader = $this->createReader();
        // setReadDataOnly(false) is required — formatting must be loaded for Date::isDateTime() to work
        $reader->setReadDataOnly(false);
        $spreadsheet = $reader->load($this->source);

        $this->processSheet($this->getWorksheet($spreadsheet));
    }

    private function processSheet(Worksheet $worksheet): void
    {
        $handle = fopen($this->destination, 'wb');

        foreach ($worksheet->getRowIterator() as $workSheetRow) {
            $cellIterator = $workSheetRow->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = Date::isDateTime($cell)
                    ? $this->getDate($cell)
                    : $this->removeNewLines($cell->getCalculatedValue());
            }

            $rowData = $this->pruneTrailingEmptyCells($rowData);

            if ($this->destination_enclosure === '') {
                fwrite($handle, implode($this->destination_delimiter, $rowData) . "\n");
            } else {
                fputcsv($handle, $rowData, $this->destination_delimiter, $this->destination_enclosure);
            }
        }

        fclose($handle);
    }

    protected function getDate(Cell $cell): string
    {
        $value = $cell->getCalculatedValue();

        if ($value === null || $value === '') {
            return '';
        }

        // Value < 1 represents a time-only serial (no date component)
        if ($value < 1) {
            return Date::excelToDateTimeObject($value)->format('H:i:s');
        }

        return Date::excelToDateTimeObject($value)->format($this->date_format);
    }

    private function getWorksheet(Spreadsheet $spreadsheet): Worksheet
    {
        if ($this->worksheet === null) {
            return $spreadsheet->getActiveSheet();
        }

        if (is_int($this->worksheet)) {
            $index = $this->worksheet - 1;
            if ($index < 0 || $index >= $spreadsheet->getSheetCount()) {
                throw new ExcelConverterException("Worksheet not found [$this->worksheet]");
            }

            return $spreadsheet->getSheet($index);
        }

        $sheet = $spreadsheet->getSheetByName($this->worksheet);

        if ($sheet === null) {
            throw new ExcelConverterException("Worksheet not found [$this->worksheet]");
        }

        return $sheet;
    }

    protected function removeNewLines(mixed $value): string
    {
        if (is_array($value)) {
            return '';
        }

        return str_replace(["\r\n", "\r", "\n"], ' ', (string) ($value ?? ''));
    }

    private function pruneTrailingEmptyCells(array $rowData): array
    {
        while (! empty($rowData) && end($rowData) === '') {
            array_pop($rowData);
        }

        return $rowData;
    }
}
