<?php

namespace LabelWorx\ExcelConverter\Facades;

use Illuminate\Support\Facades\Facade;

class ExcelConverterFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lw-excel-converter';
    }
}
