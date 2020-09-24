<?php

namespace Tests;

use LabelWorx\ExcelConverter\ExcelConverterServiceProvider;
use LabelWorx\ExcelConverter\Facades\ExcelConverter;
use Orchestra\Testbench\TestCase;

class ConverterTestCase extends TestCase
{
    const CSV_LINE_1 = 'one,two,three,four';
    const CSV_LINE_2 = 'blue,orange,green,blue';
    const CSV_LINE_3 = '"Chris, Chambers","(0123) 123 3455","Some House",3.56';

    const TSV_LINE_1 = "one\ttwo\tthree\tfour";
    const TSV_LINE_2 = "blue\torange\tgreen\tblue";
    const TSV_LINE_3 = "\"Chris, Chambers\"\t\"(0123) 123 3455\"\t\"Some House\"\t3.56";

    protected function getPackageProviders($app)
    {
        return [
            ExcelConverterServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Excel' => ExcelConverter::class,
        ];
    }
}
