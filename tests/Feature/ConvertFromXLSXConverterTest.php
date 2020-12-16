<?php

namespace Tests\Feature;

use LabelWorx\ExcelConverter\Facades\ExcelConverter;
use Tests\ConverterTestCase;

class ConvertFromXLSXConverterTest extends ConverterTestCase
{
    const XLSX_FILE = __DIR__.'/../../files/excel.xlsx';

    /** @test */
    public function an_xlsx_file_can_be_converted_to_a_csv()
    {
        $csv_file = sys_get_temp_dir().'/from_xlsx.csv';

        ExcelConverter::source(self::XLSX_FILE)->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", file_get_contents($csv_file));

        $this->assertExpectedLineCount(4, $csv_file);
        $this->assertSame(self::CSV_LINE_1, $lines[0]);
        $this->assertSame(self::CSV_LINE_2, $lines[1]);
        $this->assertSame(self::CSV_LINE_3, $lines[2]);

        unlink($csv_file);
    }

    /** @test */
    public function an_xlsx_file_can_be_converted_to_a_tsv()
    {
        $tsv_file = sys_get_temp_dir().'/from_xlsx.tsv';

        ExcelConverter::source(self::XLSX_FILE)->toTSV($tsv_file);

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", file_get_contents($tsv_file));

        $this->assertExpectedLineCount(4, $tsv_file);
        $this->assertSame(self::TSV_LINE_1, $lines[0]);
        $this->assertSame(self::TSV_LINE_2, $lines[1]);
        $this->assertSame(self::TSV_LINE_3, $lines[2]);

        unlink($tsv_file);
    }

    /** @test */
    public function an_xlsx_file_can_be_converted_to_a_tsv_without_an_enclosure()
    {
        $tsv_file = sys_get_temp_dir().'/from_xls.tsv';

        ExcelConverter::source(self::XLSX_FILE)->toTSV($tsv_file, '');

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", file_get_contents($tsv_file));

        $this->assertExpectedLineCount(4, $tsv_file);
        $this->assertSame(self::TSV_LINE_1, $lines[0]);
        $this->assertSame(self::TSV_LINE_2, $lines[1]);
        $this->assertSame(self::TSV_LINE_4, $lines[2]);

        unlink($tsv_file);
    }

    /** @test */
    public function an_xlsx_file_second_worksheet_can_converted_to_a_tsv_using_the_worksheet_name()
    {
        $tsv_file = sys_get_temp_dir().'/from_xlsx.tsv';

        ExcelConverter::source(self::XLSX_FILE)
            ->worksheet('Another Sheet')
            ->toTSV($tsv_file);

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", trim(file_get_contents($tsv_file)));

        $this->assertExpectedLineCount(2, $tsv_file);
        $this->assertSame(self::SHEET_2_TSV_LINE_1, $lines[0]);
        $this->assertSame(self::SHEET_2_TSV_LINE_2, $lines[1]);

        unlink($tsv_file);
    }

    /** @test */
    public function an_xlsx_file_third_worksheet_can_converted_to_a_csv_using_the_worksheet_name()
    {
        $csv_file = sys_get_temp_dir().'/from_xlsx.csv';

        ExcelConverter::source(self::XLSX_FILE)
            ->worksheet('Third Sheet')
            ->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", trim(file_get_contents($csv_file)));

        $this->assertExpectedLineCount(1, $csv_file);
        $this->assertSame('something', $lines[0]);

        unlink($csv_file);
    }

    /** @test */
    public function an_xlsx_file_second_worksheet_can_converted_to_a_tsv_using_the_worksheet_number()
    {
        $tsv_file = sys_get_temp_dir().'/from_xlsx.tsv';

        ExcelConverter::source(self::XLSX_FILE)
            ->worksheet(2) // 'Another Sheet'
            ->toTSV($tsv_file);

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", trim(file_get_contents($tsv_file)));

        $this->assertExpectedLineCount(2, $tsv_file);
        $this->assertSame(self::SHEET_2_TSV_LINE_1, $lines[0]);
        $this->assertSame(self::SHEET_2_TSV_LINE_2, $lines[1]);

        unlink($tsv_file);
    }

    /** @test */
    public function an_xlsx_file_third_worksheet_can_converted_to_a_csv_using_the_worksheet_number()
    {
        $csv_file = sys_get_temp_dir().'/from_xlsx.csv';

        ExcelConverter::source(self::XLSX_FILE)
            ->worksheet(3) // Third Sheet
            ->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", trim(file_get_contents($csv_file)));

        $this->assertExpectedLineCount(1, $csv_file);
        $this->assertSame('something', $lines[0]);

        unlink($csv_file);
    }
}
