<?php

namespace Tests\Converters;

use LabelWorx\ExcelConverter\Facades\ExcelConverter;
use Tests\ConverterTestCase;

class ConvertFromTSVTest extends ConverterTestCase
{
    private const TSV_FILE = __DIR__ . '/../../files/test.tsv';
    private const CSV_FILE = __DIR__ . '/../../files/out.csv';

    /** @test */
    public function a_tsv_file_can_be_converted_to_a_csv(): void
    {
        ExcelConverter::source(self::TSV_FILE)->toCSV(self::CSV_FILE);

        $this->assertFileExists(self::CSV_FILE);

        $lines = explode("\n", file_get_contents(self::CSV_FILE));

        $this->assertExpectedLineCount(3, self::CSV_FILE);
        $this->assertStringContainsString(self::CSV_LINE_1, $lines[0]);
        $this->assertStringContainsString(self::CSV_LINE_2, $lines[1]);
        $this->assertStringContainsString(self::CSV_LINE_3, $lines[2]);

        unlink(self::CSV_FILE);
    }
}
