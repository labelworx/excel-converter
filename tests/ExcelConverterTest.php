<?php

namespace Tests;

use LabelWorx\ExcelConverter\Facades\ExcelConverterFacade as Excel;
use LabelWorx\ExcelConverter\ExcelConverterServiceProvider;
use LabelWorx\ExcelConverter\Facades\ExcelConverterFacade;
use Orchestra\Testbench\TestCase;

class ExcelConverterTest extends TestCase
{
    const CSV_LINE_1 = 'name,normalised_name,new_addition,Can_be_approved,artist_uri,added_date';
    const CSV_LINE_2 = '"Mc Davi",mcdavi,1,1,spotify:artist:1cYhx7ZOhYoVmnDPb9KMwo,"2020-07-22 00:00:00"';
    const CSV_LINE_3 = '"Julion Alvarez",julionalvarez,1,0,spotify:artist:6hawG2i16WnvEEqfDiv4we,"2020-07-22 00:00:00"';

    const TSV_LINE_1 = "name\tnormalised_name\tnew_addition\tCan_be_approved\tartist_uri\tadded_date";
    const TSV_LINE_2 = "\"Mc Davi\"\tmcdavi\t1\t1\tspotify:artist:1cYhx7ZOhYoVmnDPb9KMwo\t\"2020-07-22 00:00:00\"";
    const TSV_LINE_3 = "\"Julion Alvarez\"\tjulionalvarez\t1\t0\tspotify:artist:6hawG2i16WnvEEqfDiv4we\t\"2020-07-22 00:00:00\"";

    protected function getPackageProviders($app)
    {
        return [
            ExcelConverterServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Excel' => ExcelConverterFacade::class
        ];
    }

    /** @test */
    public function an_xls_file_can_be_converted_to_a_csv()
    {
        $excel_file = __DIR__ . '/../files/excel.xls';
        $csv_file = sys_get_temp_dir().'/output.csv';

        Excel::source($excel_file)->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", file_get_contents($csv_file));

        $this->assertSame(self::CSV_LINE_1, $lines[0]);
        $this->assertSame(self::CSV_LINE_2, $lines[1]);
        $this->assertSame(self::CSV_LINE_3, $lines[2]);

        unlink($csv_file);
    }

    /** @test */
    public function an_xlsx_file_can_be_converted_to_a_csv()
    {
        $excel_file = __DIR__ . '/../files/excel.xlsx';
        $csv_file = sys_get_temp_dir().'/output.csv';

        Excel::source($excel_file)->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", file_get_contents($csv_file));

        $this->assertSame(self::CSV_LINE_1, $lines[0]);
        $this->assertSame(self::CSV_LINE_2, $lines[1]);
        $this->assertSame(self::CSV_LINE_3, $lines[2]);

        unlink($csv_file);
    }

    /** @test */
    public function an_xlsx_file_can_be_converted_to_a_tsv()
    {
        $excel_file = __DIR__ . '/../files/excel.xlsx';
        $tsv_file = sys_get_temp_dir().'/output.tsv';

        Excel::source($excel_file)->toTSV($tsv_file);

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", file_get_contents($tsv_file));

        $this->assertSame(self::TSV_LINE_1, $lines[0]);
        $this->assertSame(self::TSV_LINE_2, $lines[1]);
        $this->assertSame(self::TSV_LINE_3, $lines[2]);

        unlink($tsv_file);
    }

}
