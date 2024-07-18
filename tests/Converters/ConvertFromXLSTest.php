<?php

namespace Tests\Converters;

use LabelWorx\ExcelConverter\Exceptions\ExcelConverterException;
use LabelWorx\ExcelConverter\Facades\ExcelConverter;
use Tests\ConverterTestCase;

class ConvertFromXLSTest extends ConverterTestCase
{
    private const XLS_FILE = __DIR__ . '/../../files/excel.xls';

    /** @test */
    public function an_xls_file_can_be_converted_to_a_csv(): void
    {
        $csv_file = sys_get_temp_dir() . '/from_xls.csv';

        ExcelConverter::source(self::XLS_FILE)->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", file_get_contents($csv_file));

        $this->assertExpectedLineCount(6, $csv_file);
        $this->assertSame(self::XLS_CONVERTED_CSV_LINE_1, $lines[0]);
        $this->assertSame(self::XLS_CONVERTED_CSV_LINE_2, $lines[1]);
        $this->assertSame(self::XLS_CONVERTED_CSV_LINE_3, $lines[2]);
        $this->assertSame(self::XLS_CONVERTED_CSV_LINE_5, $lines[3]);

        unlink($csv_file);
    }

    /** @test */
    public function an_xls_file_can_be_converted_to_a_csv_with_date_format(): void
    {
        $csv_file = sys_get_temp_dir() . '/from_xls.csv';

        ExcelConverter::source(self::XLS_FILE)->exportDateFormat('d/m/Y')->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", file_get_contents($csv_file));

        $this->assertExpectedLineCount(6, $csv_file);
        $this->assertSame(self::CSV_LINE_1, $lines[0]);
        $this->assertSame(self::CSV_LINE_2, $lines[1]);
        $this->assertSame(self::CSV_LINE_3, $lines[2]);
        $this->assertSame(self::CSV_LINE_4, $lines[3]);

        unlink($csv_file);
    }

    /** @test */
    public function an_xls_file_can_be_converted_to_a_tsv(): void
    {
        $tsv_file = sys_get_temp_dir() . '/from_xls.tsv';

        ExcelConverter::source(self::XLS_FILE)->toTSV($tsv_file);

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", file_get_contents($tsv_file));

        $this->assertExpectedLineCount(4, $tsv_file);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_1, $lines[0]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_2, $lines[1]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_3, $lines[2]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_6, $lines[3]);

        unlink($tsv_file);
    }

    /** @test */
    public function an_xls_file_can_be_converted_to_a_tsv_without_an_enclosure(): void
    {
        $tsv_file = sys_get_temp_dir() . '/from_xls.tsv';

        ExcelConverter::source(self::XLS_FILE)->toTSV($tsv_file, '');

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", file_get_contents($tsv_file));

        $this->assertExpectedLineCount(4, $tsv_file);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_1, $lines[0]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_2, $lines[1]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_4, $lines[2]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_7, $lines[3]);

        unlink($tsv_file);
    }

    /** @test */
    public function destination_file_can_be_overwritten(): void
    {
        $tsv_file = __DIR__ . '/../../files/from_xls.tsv';

        copy(__DIR__ . '/../../files/excel.xls', $tsv_file);

        ExcelConverter::source(self::XLS_FILE)->toTSV($tsv_file);

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", file_get_contents($tsv_file));

        $this->assertExpectedLineCount(4, $tsv_file);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_1, $lines[0]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_2, $lines[1]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_3, $lines[2]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_6, $lines[3]);

        unlink($tsv_file);
    }

    /** @test */
    public function an_xls_file_second_worksheet_can_converted_to_a_tsv_using_the_worksheet_name(): void
    {
        $tsv_file = sys_get_temp_dir() . '/output.tsv';

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
    public function an_xls_file_third_worksheet_can_converted_to_a_csv_using_the_worksheet_name(): void
    {
        $csv_file = sys_get_temp_dir() . '/output.csv';

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
    public function an_xls_file_second_worksheet_can_converted_to_a_tsv_using_the_worksheet_number(): void
    {
        $tsv_file = sys_get_temp_dir() . '/output.tsv';

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
    public function an_xls_file_third_worksheet_can_converted_to_a_csv_using_the_worksheet_number(): void
    {
        $csv_file = sys_get_temp_dir() . '/output.csv';

        ExcelConverter::source(self::XLS_FILE)
            ->worksheet(3) // Third
            ->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", trim(file_get_contents($csv_file)));

        $this->assertExpectedLineCount(1, $csv_file);
        $this->assertSame('something', $lines[0]);

        unlink($csv_file);
    }

    /** @test */
    public function an_exception_is_thrown_if_the_specified_worksheet_does_not_exist_in_the_xls(): void
    {
        $csv_file = sys_get_temp_dir() . '/output.csv';

        $this->expectException(ExcelConverterException::class);
        $this->expectExceptionMessage('Worksheet not found [invalid]');

        ExcelConverter::source(self::XLS_FILE)
            ->worksheet('invalid')
            ->toCSV($csv_file);

        $this->assertFileDoesNotExist($csv_file);
    }
}
