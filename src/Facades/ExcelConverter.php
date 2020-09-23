<?php

namespace LabelWorx\ExcelConverter\Facades;

use Illuminate\Support\Facades\Facade;

class ExcelConverter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lw-excel-converter';
    }
}
