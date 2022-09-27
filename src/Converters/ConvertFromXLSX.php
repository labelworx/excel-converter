<?php

namespace LabelWorx\ExcelConverter\Converters;

use DateTime;
use LabelWorx\ExcelConverter\Exceptions\ExcelConverterException;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ConvertFromXLSX extends BaseConverter
{
    public function convert(): void
    {
        $reader = new Xlsx();
        $reader->setReadDataOnly(false);
        $spreadsheet = $reader->load($this->source);

        $worksheet = $this->getWorksheet($spreadsheet);

        $this->processSheet($worksheet);
    }

    private function processSheet($worksheet): void
    {
        $handle = fopen($this->destination, 'wb');

        foreach ($worksheet->getRowIterator() as $workSheetRow) {
            $cellIterator = $workSheetRow->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                if (Date::isDateTime($cell)) {
                    $rowData[] = $this->getDate($cell);

                    continue;
                }

                $rowData[] = $this->removeNewLines($cell->getValue());
            }

            $rowData = $this->pruneEmptyLastCell($rowData);

            if ($this->destination_enclosure === '') {
                fwrite($handle, implode($this->destination_delimiter, $rowData) . "\n");
            } else {
                fputcsv($handle, $rowData, $this->destination_delimiter, $this->destination_enclosure);
            }
        }

        fclose($handle);
    }

    private function removeNewLines($string): string
    {
        return str_replace("\n", ' ', $string);
    }

    private function getDate($cell): string
    {
        return (new DateTime())->setTimestamp(Date::excelToTimestamp($cell->getValue()))->format($this->date_format);
    }

    private function getWorksheet($spreadsheet)
    {
        if ($this->worksheet === null) {
            return $spreadsheet->getActiveSheet();
        }

        $worksheet = null;

        if (is_string($this->worksheet)) {
            $worksheet = $spreadsheet->getSheetByName($this->worksheet);
        }

        if (is_null($worksheet) && is_numeric($this->worksheet)) {
            $worksheet = $spreadsheet->getSheet((int) $this->worksheet - 1);
        }

        if (is_null($worksheet)) {
            throw new ExcelConverterException("Worksheet not found [$this->worksheet]");
        }

        return $worksheet;
    }

    private function pruneEmptyLastCell($rowData)
    {
        $count = count($rowData) - 1;

        if ($rowData[$count] === '') {
            unset($rowData[$count]);
        }

        return $rowData;
    }
}
