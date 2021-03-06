<?php

namespace Tests;

use LabelWorx\ExcelConverter\ExcelConverterServiceProvider;
use LabelWorx\ExcelConverter\Facades\ExcelConverter;
use Orchestra\Testbench\TestCase;

class ConverterTestCase extends TestCase
{
    const CSV_LINE_1 = 'one,two,three,four,date';
    const CSV_LINE_2 = 'blue,orange,green,blue,01/08/2020';
    const CSV_LINE_3 = '"Chris, Chambers","(0123) 123 3455","Some House",3.56,01/08/2020';

    const TSV_LINE_1 = "one\ttwo\tthree\tfour\tdate";
    const TSV_LINE_2 = "blue\torange\tgreen\tblue\t01/08/2020";
    const TSV_LINE_3 = "\"Chris, Chambers\"\t\"(0123) 123 3455\"\t\"Some House\"\t3.56\t01/08/2020";
    const TSV_LINE_4 = "Chris, Chambers\t(0123) 123 3455\tSome House\t3.56\t01/08/2020";

    const XLS_CONVERTED_CSV_LINE_1 = 'one,two,three,four,date';
    const XLS_CONVERTED_CSV_LINE_2 = 'blue,orange,green,blue,2020-08-01';
    const XLS_CONVERTED_CSV_LINE_3 = '"Chris, Chambers","(0123) 123 3455","Some House",3.56,2020-08-01';

    const XLS_CONVERTED_TSV_LINE_1 = "one\ttwo\tthree\tfour\tdate";
    const XLS_CONVERTED_TSV_LINE_2 = "blue\torange\tgreen\tblue\t2020-08-01";
    const XLS_CONVERTED_TSV_LINE_3 = "\"Chris, Chambers\"\t\"(0123) 123 3455\"\t\"Some House\"\t3.56\t2020-08-01";
    const XLS_CONVERTED_TSV_LINE_4 = "Chris, Chambers\t(0123) 123 3455\tSome House\t3.56\t2020-08-01";

    const SHEET_2_TSV_LINE_1 = "pink\tgreen\tblue";
    const SHEET_2_TSV_LINE_2 = "red\tpurple\torange";

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

    public function assertExpectedLineCount($expected, $file)
    {
        $contents = file_get_contents($file);
        $lines = explode("\n", trim($contents));
        self::assertSame($expected, count($lines), 'Line count does not match');
    }
}
