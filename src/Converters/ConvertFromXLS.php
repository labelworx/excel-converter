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

        $worksheet = $spreadsheet->getActiveSheet();

        $handle = fopen($this->destination, 'w');

        foreach ($worksheet->getRowIterator() as $workSheetRow) {
            $cellIterator = $workSheetRow->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            fputcsv($handle, $rowData, $this->destination_delimiter, $this->destination_enclosure);
        }

        fclose($handle);
    }
}
