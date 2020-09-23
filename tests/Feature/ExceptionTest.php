<?php

namespace Tests\Feature;

use LabelWorx\ExcelConverter\Facades\ExcelConverter;
use Tests\ConverterTestCase;

class ExceptionTest extends ConverterTestCase
{
    const XLS_FILE   = __DIR__ . '/../../files/excel.xls';
    const CSV_FILE   = __DIR__ . '/../../files/final.csv';

    /** @test */
    public function an_unsupported_file_type_throws_an_exception()
    {
        $source_file = __DIR__ . '/../../files/unknown.dat';

        try {
            ExcelConverter::source($source_file)->toCSV(self::CSV_FILE);
        } catch(\Exception $e) {
            $this->assertSame('File type not supported [dat]', $e->getMessage());
        }
    }

    /** @test */
    public function none_string_source_file_throws_an_exception()
    {
        $source_file = [ 'something' ];

        try {
            ExcelConverter::source($source_file)->toCSV(self::CSV_FILE);
        } catch(\Exception $e) {
            $this->assertSame('Specified source file should be a string', $e->getMessage());
        }
    }

    /** @test */
    public function source_file_is_directory_throws_an_exception()
    {
        $source_file = __DIR__ . '/../../files';

        try {
            ExcelConverter::source($source_file)->toCSV(self::CSV_FILE);
        } catch(\Exception $e) {
            $this->assertSame('Specified source file is a directory', $e->getMessage());
        }
    }

    /** @test */
    public function source_file_does_not_exist_throws_an_exception()
    {
        $source_file = __DIR__ . '/../../files/nothing';

        try {
            ExcelConverter::source($source_file)->toCSV(self::CSV_FILE);
        } catch(\Exception $e) {
            $this->assertSame('Specified source does not exist', $e->getMessage());
        }
    }

    /** @test */
    public function destination_file_is_a_directory_throws_an_exception()
    {
        $source_file = __DIR__ . '/../../files/excel.xls';
        $destination_file = __DIR__ . '/../../files';

        try {
            ExcelConverter::source($source_file)->toCSV($destination_file);
        } catch(\Exception $e) {
            $this->assertSame('Destination file is directory', $e->getMessage());
        }
    }

    /** @test */
    public function no_source_file_throws_an_exception()
    {
        try {
            ExcelConverter::toCSV(self::CSV_FILE);
        } catch(\Exception $e) {
            $this->assertSame('You did not specify a source file', $e->getMessage());
        }
    }

    /** @test */
    public function no_destination_file_throws_an_exception()
    {
        $source_file = __DIR__ . '/../../files/excel.xls';

        try {
            ExcelConverter::source($source_file)->toCSV('');
        } catch(\Exception $e) {
            $this->assertSame('You did not specify a destination file', $e->getMessage());
        }
    }

}
