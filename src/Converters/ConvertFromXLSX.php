<?php

namespace LabelWorx\ExcelConverter\Converters;

use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ConvertFromXLSX extends AbstractConvertFromExcel
{
    protected function createReader(): IReader
    {
        return new Xlsx;
    }
}
