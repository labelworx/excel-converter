<?php

namespace LabelWorx\ExcelConverter\Converters;

use DateTime;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ConvertFromXLS extends BaseConverter
{
    public function convert(): void
    {
        $reader = new Xls();
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
                fwrite($handle, implode($this->destination_delimiter, $rowData)."\n");
            } else {
                fputcsv($handle, $rowData, $this->destination_delimiter, $this->destination_enclosure);
            }
        }

        fclose($handle);
    }

    private function removeNewLines($string): string
    {
        return str_replace("\n", " ", $string);
    }

    private function getDate($cell): string
    {
        return (new DateTime())->setTimestamp(Date::excelToTimestamp($cell->getValue()))->format($this->date_format);
    }

    private function getWorksheet($spreadsheet)
    {
        if (is_string($this->worksheet)) {
            return $spreadsheet->getSheetByName($this->worksheet);
        }

        if (is_int($this->worksheet)) {
            return $spreadsheet->getSheet($this->worksheet - 1);
        }

        return $spreadsheet->getActiveSheet();
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
