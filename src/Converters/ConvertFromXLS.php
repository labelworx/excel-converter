<?php

namespace LabelWorx\ExcelConverter\Converters;

use PhpOffice\PhpSpreadsheet\Reader\Xls;

class ConvertFromXLS extends BaseConverter
{
    public function convert()
    {
        $reader = new Xls();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->source);

        $worksheet = $this->getWorksheet($spreadsheet);

        $this->processSheet($worksheet);
    }

    private function processSheet($worksheet)
    {
        $handle = fopen($this->destination, 'w');

        foreach ($worksheet->getRowIterator() as $workSheetRow) {
            $cellIterator = $workSheetRow->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            $rowData = $this->pruneEmptyLastCell($rowData);

            fputcsv($handle, $rowData, $this->destination_delimiter, $this->destination_enclosure);
        }

        fclose($handle);
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

        if ($rowData[$count] == '') {
            unset($rowData[$count]);
        }

        return $rowData;
    }

}
