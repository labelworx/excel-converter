<?php

namespace Tests\Feature;

use LabelWorx\ExcelConverter\Facades\ExcelConverter;
use Tests\ConverterTestCase;

class ConvertFromCustomConverterTest extends ConverterTestCase
{
    const TSV_FILE = __DIR__.'/../../files/test.tsv';
    const CSV_FILE = __DIR__.'/../../files/out.csv';

    /** @test */
    public function a_tsv_file_can_be_converted_to_a_csv()
    {
        ExcelConverter::source(self::TSV_FILE, "\t", '"')->toCSV(self::CSV_FILE);

        $this->assertFileExists(self::CSV_FILE);

        $lines = explode("\n", file_get_contents(self::CSV_FILE));

        $this->assertExpectedLineCount(3, self::CSV_FILE);
        $this->assertStringContainsString(self::CSV_LINE_1, $lines[0]);
        $this->assertStringContainsString(self::CSV_LINE_2, $lines[1]);
        $this->assertStringContainsString(self::CSV_LINE_3, $lines[2]);

        unlink(self::CSV_FILE);
    }

    /** @test */
    public function a_custom_file_can_be_converted_to_a_custom_file()
    {
        $input = __DIR__.'/../../files/semi-colon.csv';
        $output = __DIR__.'/../../files/pipe.csv';

        ExcelConverter::source($input)->sourceDelimiter(';')->to($output, '|');

        $this->assertFileExists($output);

        $lines = explode("\n", file_get_contents($output));

        $this->assertExpectedLineCount(3, $output);
        $this->assertStringContainsString('one|two|three|four', $lines[0]);
        $this->assertStringContainsString('blue|orange|green|blue', $lines[1]);
        $this->assertStringContainsString('"Chris, Chambers"|"(0123) 123 3455"|"Some House"|3.56', $lines[2]);

        unlink($output);
    }
}
