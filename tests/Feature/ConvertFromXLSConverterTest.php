<?php

namespace Tests\Feature;

use LabelWorx\ExcelConverter\Facades\ExcelConverter;
use Tests\ConverterTestCase;

class ConvertFromXLSConverterTest extends ConverterTestCase
{
    const XLS_FILE   = __DIR__ . '/../../files/excel.xls';

    /** @test */
    public function an_xls_file_can_be_converted_to_a_csv()
    {
        $csv_file = sys_get_temp_dir().'/from_xls.csv';

        ExcelConverter::source(self::XLS_FILE)->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", file_get_contents($csv_file));

        $this->assertSame(self::CSV_LINE_1, $lines[0]);
        $this->assertSame(self::CSV_LINE_2, $lines[1]);
        $this->assertSame(self::CSV_LINE_3, $lines[2]);

        unlink($csv_file);
    }

    /** @test */
    public function an_xls_file_can_be_converted_to_a_tsv()
    {
        $tsv_file = sys_get_temp_dir().'/from_xls.tsv';

        ExcelConverter::source(self::XLS_FILE)->toTSV($tsv_file);

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", file_get_contents($tsv_file));

        $this->assertSame(self::TSV_LINE_1, $lines[0]);
        $this->assertSame(self::TSV_LINE_2, $lines[1]);
        $this->assertSame(self::TSV_LINE_3, $lines[2]);

        unlink($tsv_file);
    }

}
