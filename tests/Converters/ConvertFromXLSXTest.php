<?php

namespace Tests\Converters;

use LabelWorx\ExcelConverter\Converters\ConvertFromXLSX;
use LabelWorx\ExcelConverter\Exceptions\ExcelConverterException;
use LabelWorx\ExcelConverter\Facades\ExcelConverter;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PHPUnit\Framework\Attributes\Test;
use Tests\ConverterTestCase;

class ConvertFromXLSXTest extends ConverterTestCase
{
    private const XLSX_FILE = __DIR__ . '/../../files/excel.xlsx';

    #[Test]
    public function an_xlsx_file_can_be_converted_to_a_csv(): void
    {
        $csv_file = sys_get_temp_dir() . '/from_xlsx.csv';

        ExcelConverter::source(self::XLSX_FILE)->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", file_get_contents($csv_file));

        $this->assertExpectedLineCount(5, $csv_file);

        $this->assertSame(self::XLS_CONVERTED_CSV_LINE_1, $lines[0]);
        $this->assertSame(self::XLS_CONVERTED_CSV_LINE_2, $lines[1]);
        $this->assertSame(self::XLS_CONVERTED_CSV_LINE_3, $lines[2]);
        $this->assertSame(self::XLS_CONVERTED_CSV_LINE_4, $lines[3]);
        $this->assertSame(self::XLS_CONVERTED_CSV_LINE_5, $lines[4]);

        unlink($csv_file);
    }

    #[Test]
    public function an_xlsx_file_can_be_converted_to_a_tsv(): void
    {
        $tsv_file = sys_get_temp_dir() . '/from_xlsx.tsv';

        ExcelConverter::source(self::XLSX_FILE)->toTSV($tsv_file);

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", file_get_contents($tsv_file));

        $this->assertExpectedLineCount(5, $tsv_file);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_1, $lines[0]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_2, $lines[1]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_3, $lines[2]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_5, $lines[3]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_6, $lines[4]);

        unlink($tsv_file);
    }

    #[Test]
    public function an_xlsx_file_can_be_converted_to_a_tsv_without_an_enclosure(): void
    {
        $tsv_file = sys_get_temp_dir() . '/from_xls.tsv';

        ExcelConverter::source(self::XLSX_FILE)->toTSV($tsv_file, '');

        $this->assertFileExists($tsv_file);

        $lines = explode("\n", file_get_contents($tsv_file));

        $this->assertExpectedLineCount(5, $tsv_file);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_1, $lines[0]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_2, $lines[1]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_4, $lines[2]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_5, $lines[3]);
        $this->assertSame(self::XLS_CONVERTED_TSV_LINE_7, $lines[4]);

        unlink($tsv_file);
    }

    #[Test]
    public function an_xlsx_file_second_worksheet_can_converted_to_a_tsv_using_the_worksheet_name(): void
    {
        $tsv_file = sys_get_temp_dir() . '/from_xlsx.tsv';

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

    #[Test]
    public function an_xlsx_file_third_worksheet_can_converted_to_a_csv_using_the_worksheet_name(): void
    {
        $csv_file = sys_get_temp_dir() . '/from_xlsx.csv';

        ExcelConverter::source(self::XLSX_FILE)
            ->worksheet('Third Sheet')
            ->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", trim(file_get_contents($csv_file)));

        $this->assertExpectedLineCount(1, $csv_file);
        $this->assertSame('something', $lines[0]);

        unlink($csv_file);
    }

    #[Test]
    public function an_xlsx_file_second_worksheet_can_converted_to_a_tsv_using_the_worksheet_number(): void
    {
        $tsv_file = sys_get_temp_dir() . '/from_xlsx.tsv';

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

    #[Test]
    public function an_xlsx_file_third_worksheet_can_converted_to_a_csv_using_the_worksheet_number(): void
    {
        $csv_file = sys_get_temp_dir() . '/from_xlsx.csv';

        ExcelConverter::source(self::XLSX_FILE)
            ->worksheet(3) // Third Sheet
            ->toCSV($csv_file);

        $this->assertFileExists($csv_file);

        $lines = explode("\n", trim(file_get_contents($csv_file)));

        $this->assertExpectedLineCount(1, $csv_file);
        $this->assertSame('something', $lines[0]);

        unlink($csv_file);
    }

    #[Test]
    public function empty_date_formatted_cell_produces_empty_string(): void
    {
        $converter = new class('', '', ',', '"', '', '', null, 'Y-m-d') extends ConvertFromXLSX {
            public function callGetDate(Cell $cell): string
            {
                return $this->getDate($cell);
            }
        };

        // A cell with a date format code but no value — getCalculatedValue() returns null
        $cell = (new Spreadsheet())->getActiveSheet()->getCell('A1');
        $cell->getStyle()->getNumberFormat()->setFormatCode('yyyy-mm-dd');

        $this->assertSame('', $converter->callGetDate($cell));
    }

    #[Test]
    public function an_exception_is_thrown_when_integer_worksheet_number_is_out_of_bounds(): void
    {
        $csv_file = sys_get_temp_dir() . '/output.csv';

        $this->expectException(ExcelConverterException::class);
        $this->expectExceptionMessage('Worksheet not found [99]');

        ExcelConverter::source(self::XLSX_FILE)
            ->worksheet(99)
            ->toCSV($csv_file);
    }

    #[Test]
    public function cells_with_array_formula_values_produce_empty_string_without_throwing(): void
    {
        $converter = new class('', '', ',', '"', '', '', null, 'Y-m-d') extends ConvertFromXLSX {
            public function callRemoveNewLines(mixed $value): string
            {
                return $this->removeNewLines($value);
            }
        };

        $this->assertSame('', $converter->callRemoveNewLines([]));
        $this->assertSame('', $converter->callRemoveNewLines(['formula', 'result']));
    }

    #[Test]
    public function an_exception_is_thrown_if_the_specified_worksheet_does_not_exist_in_the_xlsx(): void
    {
        $csv_file = sys_get_temp_dir() . '/output.csv';

        $this->expectException(ExcelConverterException::class);
        $this->expectExceptionMessage('Worksheet not found [invalid]');

        ExcelConverter::source(self::XLSX_FILE)
            ->worksheet('invalid')
            ->toCSV($csv_file);

        $this->assertFileDoesNotExist($csv_file);
    }
}
