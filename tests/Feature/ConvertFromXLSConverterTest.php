<?php

namespace Tests\Feature;

use LabelWorx\ExcelConverter\Facades\ExcelConverter;
use Tests\ConverterTestCase;

class ConvertFromXLSConverterTest extends ConverterTestCase
{
    const XLS_FILE = __DIR__.'/../../files/excel.xls';

    /** @test */
    public function an_xls_file_can_be_converted_to_a_csv()
    {
        $csv_file = sys_get_temp_dir().'/from_xls.csv';

        ExcelConverter::source(self::XLS_FILE)->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", file_get_contents($csv_file));

        $this->assertExpectedLineCount(3, $csv_file);
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

        $this->assertExpectedLineCount(3, $tsv_file);
        $this->assertSame(self::TSV_LINE_1, $lines[0]);
        $this->assertSame(self::TSV_LINE_2, $lines[1]);
        $this->assertSame(self::TSV_LINE_3, $lines[2]);

        unlink($tsv_file);
    }

    /** @test */
    public function destination_file_can_be_overwritten()
    {
        $tsv_file = __DIR__.'/../../files/from_xls.tsv';

        copy(__DIR__.'/../../files/excel.xls', $tsv_file);

        ExcelConverter::source(self::XLS_FILE)->toTSV($tsv_file);

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", file_get_contents($tsv_file));

        $this->assertExpectedLineCount(3, $tsv_file);
        $this->assertSame(self::TSV_LINE_1, $lines[0]);
        $this->assertSame(self::TSV_LINE_2, $lines[1]);
        $this->assertSame(self::TSV_LINE_3, $lines[2]);

        unlink($tsv_file);
    }

    /** @test */
    public function an_xls_file_second_worksheet_can_converted_to_a_tsv_using_the_worksheet_name()
    {
        $tsv_file = sys_get_temp_dir().'/output.tsv';

        ExcelConverter::source(self::XLS_FILE)
            ->worksheet('Second Sheet')
            ->toTSV($tsv_file);

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", trim(file_get_contents($tsv_file)));

        $this->assertExpectedLineCount(2, $tsv_file);
        $this->assertSame(self::SHEET_2_TSV_LINE_1, $lines[0]);
        $this->assertSame(self::SHEET_2_TSV_LINE_2, $lines[1]);

        unlink($tsv_file);
    }

    /** @test */
    public function an_xls_file_third_worksheet_can_converted_to_a_csv_using_the_worksheet_name()
    {
        $csv_file = sys_get_temp_dir().'/output.csv';

        ExcelConverter::source(self::XLS_FILE)
            ->worksheet('Third')
            ->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", trim(file_get_contents($csv_file)));

        $this->assertExpectedLineCount(1, $csv_file);
        $this->assertSame('something', $lines[0]);

        unlink($csv_file);
    }

    /** @test */
    public function an_xls_file_second_worksheet_can_converted_to_a_tsv_using_the_worksheet_number()
    {
        $tsv_file = sys_get_temp_dir().'/output.tsv';

        ExcelConverter::source(self::XLS_FILE)
            ->worksheet(2) // Second Sheet
            ->toTSV($tsv_file);

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", trim(file_get_contents($tsv_file)));

        $this->assertExpectedLineCount(2, $tsv_file);
        $this->assertSame(self::SHEET_2_TSV_LINE_1, $lines[0]);
        $this->assertSame(self::SHEET_2_TSV_LINE_2, $lines[1]);

        unlink($tsv_file);
    }

    /** @test */
    public function an_xls_file_third_worksheet_can_converted_to_a_csv_using_the_worksheet_number()
    {
        $csv_file = sys_get_temp_dir().'/output.csv';

        ExcelConverter::source(self::XLS_FILE)
            ->worksheet(3) // Third
            ->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", trim(file_get_contents($csv_file)));

        $this->assertExpectedLineCount(1, $csv_file);
        $this->assertSame('something', $lines[0]);

        unlink($csv_file);
    }
}
