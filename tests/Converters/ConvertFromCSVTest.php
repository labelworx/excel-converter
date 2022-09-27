<?php

namespace Tests\Converters;

use LabelWorx\ExcelConverter\Facades\ExcelConverter;
use Tests\ConverterTestCase;

class ConvertFromCSVTest extends ConverterTestCase
{
    private const CSV_FILE = __DIR__.'/../../files/test.csv';
    private const TSV_FILE = __DIR__.'/../../files/out.tsv';

    /** @test */
    public function an_csv_file_can_be_converted_to_a_tsv(): void
    {
        ExcelConverter::source(self::CSV_FILE)->toTSV(self::TSV_FILE);

        $this->assertFileExists(self::TSV_FILE);

        $lines = explode("\n", file_get_contents(self::TSV_FILE));

        $this->assertExpectedLineCount(3, self::TSV_FILE);
        $this->assertStringContainsString(self::TSV_LINE_1, $lines[0]);
        $this->assertStringContainsString(self::TSV_LINE_2, $lines[1]);
        $this->assertStringContainsString(self::TSV_LINE_3, $lines[2]);

        unlink(self::TSV_FILE);
    }
}
