<?php

namespace LabelWorx\ExcelConverter\Facades;

use Illuminate\Support\Facades\Facade;

class ExcelConverter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'lw-excel-converter';
    }
}
