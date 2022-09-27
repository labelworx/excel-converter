<?php

namespace LabelWorx\ExcelConverter;

use LabelWorx\ExcelConverter\Converters\ConvertFromCSV;
use LabelWorx\ExcelConverter\Converters\ConvertFromCustom;
use LabelWorx\ExcelConverter\Converters\ConvertFromTSV;
use LabelWorx\ExcelConverter\Converters\ConvertFromXLS;
use LabelWorx\ExcelConverter\Converters\ConvertFromXLSX;
use LabelWorx\ExcelConverter\Exceptions\ExcelConverterException;

class ExcelConverter
{
    private string $source;
    private ?string $worksheet = null;
    private string $destination;
    private string $file_type;
    private string $source_delimiter = '';
    private string $source_enclosure = '';
    private string $destination_delimiter;
    private string $destination_enclosure;
    private string $date_format = 'Y-m-d';

    /**
     * @throws ExcelConverterException
     */
    public function source($file, $delimiter = null, $enclosure = null): self
    {
        $this->source = $this->validateSource($file);

        if ($delimiter) {
            $this->source_delimiter = $delimiter;
        }

        if ($enclosure) {
            $this->source_enclosure = $enclosure;
        }

        $this->detectFileType();

        return $this;
    }

    /**
     * @throws ExcelConverterException
     */
    private function validateSource($file): string
    {
        if (! is_string($file)) {
            throw new ExcelConverterException('Specified source file should be a string');
        }

        if (is_dir($file)) {
            throw new ExcelConverterException('Specified source file is a directory');
        }

        if (! file_exists($file)) {
            throw new ExcelConverterException('Specified source does not exist');
        }

        if (! is_readable($file)) {
            throw new ExcelConverterException('Specified source file is not readable');
        }

        return $file;
    }

    public function worksheet(string $sheet): self
    {
        $this->worksheet = $sheet;

        return $this;
    }

    /**
     * @throws ExcelConverterException
     */
    public function to($destination, $delimiter = ',', $enclosure = '"'): void
    {
        $this->destination = $this->checkDestinationFile($destination);
        $this->destination_delimiter = $delimiter;
        $this->destination_enclosure = $enclosure;

        $this->checkIfCanConvert();

        $this->doConversion();
    }

    /**
     * @throws ExcelConverterException
     */
    public function toCSV($destination): void
    {
        $this->to($destination);
    }

    /**
     * @throws ExcelConverterException
     */
    public function toTSV($destination, $enclosure = '"'): void
    {
        $this->to($destination, "\t", $enclosure);
    }

    public function sourceDelimiter($delimiter): self
    {
        $this->source_delimiter = $delimiter;

        return $this;
    }

    public function exportDateFormat($format): self
    {
        $this->date_format = $format;

        return $this;
    }

    /**
     * @throws ExcelConverterException
     */
    private function checkDestinationFile($destination): string
    {
        if (is_dir($destination)) {
            throw new ExcelConverterException('Destination file is directory');
        }

        if (file_exists($destination)) {
            unlink($destination);
        }

        return $destination;
    }

    /**
     * @throws ExcelConverterException
     */
    private function checkIfCanConvert(): void
    {
        if (! isset($this->source)) {
            throw new ExcelConverterException('You did not specify a source file');
        }

        if (! $this->destination) {
            throw new ExcelConverterException('You did not specify a destination file');
        }
    }

    private function detectFileType(): void
    {
        $info = pathinfo($this->source);
        $this->file_type = strtolower($info['extension']);

        if (! array_key_exists($this->file_type, $this->registeredConverters())) {
            throw new ExcelConverterException('File type not supported [' . $this->file_type . ']');
        }
    }

    private function registeredConverters(): array
    {
        return [
            'csv' => ConvertFromCSV::class,
            'custom' => ConvertFromCustom::class,
            'tsv' => ConvertFromTSV::class,
            'xls' => ConvertFromXLS::class,
            'xlsx' => ConvertFromXLSX::class,
        ];
    }

    private function doConversion(): void
    {
        $converters = $this->registeredConverters();

        $converter = $this->source_delimiter ? 'custom' : $this->file_type;

        $converter = new $converters[$converter](
            $this->source,
            $this->destination,
            $this->destination_delimiter,
            $this->destination_enclosure,
            $this->source_delimiter,
            $this->source_enclosure,
            $this->worksheet,
            $this->date_format,
        );

        $converter->convert();
    }
}
