<?php

namespace LabelWorx\ExcelConverter\Converters;

use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class ConvertFromXLS extends AbstractConvertFromExcel
{
    protected function createReader(): IReader
    {
        return new Xls;
    }
}
