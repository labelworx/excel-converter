<?php

namespace Tests;

use LabelWorx\ExcelConverter\Exceptions\ExcelConverterException;
use LabelWorx\ExcelConverter\Facades\ExcelConverter;

class ExceptionTest extends ConverterTestCase
{
    private const XLS_FILE = __DIR__ . '/../files/excel.xls';
    private const UNREADABLE_FILE = __DIR__ . '/../files/unreadable.xls';
    private const CSV_FILE = __DIR__ . '/../files/final.csv';

    /** @test */
    public function an_unsupported_file_type_throws_an_exception(): void
    {
        $source_file = __DIR__ . '/../files/unknown.dat';

        $this->expectException(ExcelConverterException::class);
        $this->expectExceptionMessage('File type not supported [dat]');

        ExcelConverter::source($source_file)->toCSV(self::CSV_FILE);
    }

    /** @test */
    public function none_string_source_file_throws_an_exception(): void
    {
        $source_file = ['something'];

        $this->expectException(ExcelConverterException::class);
        $this->expectExceptionMessage('Specified source file should be a string');

        ExcelConverter::source($source_file)->toCSV(self::CSV_FILE);
    }

    /** @test */
    public function source_file_is_directory_throws_an_exception(): void
    {
        $source_file = __DIR__ . '/../files';

        $this->expectException(ExcelConverterException::class);
        $this->expectExceptionMessage('Specified source file is a directory');

        ExcelConverter::source($source_file)->toCSV(self::CSV_FILE);
    }

    /** @test */
    public function source_file_does_not_exist_throws_an_exception(): void
    {
        $source_file = __DIR__ . '/../files/nothing';

        $this->expectException(ExcelConverterException::class);
        $this->expectExceptionMessage('Specified source does not exist');

        ExcelConverter::source($source_file)->toCSV(self::CSV_FILE);
    }

    /** @test */
    public function destination_file_is_a_directory_throws_an_exception(): void
    {
        $source_file = __DIR__ . '/../files/excel.xls';
        $destination_file = __DIR__ . '/../files';

        $this->expectException(ExcelConverterException::class);
        $this->expectExceptionMessage('Destination file is directory');

        ExcelConverter::source($source_file)->toCSV($destination_file);
    }

    /** @test */
    public function no_source_file_throws_an_exception(): void
    {
        $this->expectException(ExcelConverterException::class);
        $this->expectExceptionMessage('You did not specify a source file');

        ExcelConverter::toCSV(self::CSV_FILE);
    }

    /** @test */
    public function no_destination_file_throws_an_exception(): void
    {
        $source_file = __DIR__ . '/../files/excel.xls';

        $this->expectException(ExcelConverterException::class);
        $this->expectExceptionMessage('You did not specify a destination file');

        ExcelConverter::source($source_file)->toCSV('');
    }

    /** @test */
    public function an_exception_is_thrown_when_the_worksheet_cannot_be_found(): void
    {
        $source_file = __DIR__ . '/../files/excel.xls';

        $this->expectException(ExcelConverterException::class);
        $this->expectExceptionMessage('Worksheet not found [foobar]');

        ExcelConverter::source($source_file)
            ->worksheet('foobar')
            ->toCSV(__DIR__ . '/../files/output.csv');
    }

    /** @test */
    public function an_exception_is_thrown_when_a_file_is_unreadable(): void
    {
        copy(self::XLS_FILE, self::UNREADABLE_FILE);
        chmod(self::UNREADABLE_FILE, 0222);

        $this->expectException(ExcelConverterException::class);
        $this->expectExceptionMessage('Specified source file is not readable');

        ExcelConverter::source(self::UNREADABLE_FILE)->toCSV(__DIR__ . '/../files/output.csv');
    }
}
