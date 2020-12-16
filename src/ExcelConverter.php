<?php

namespace LabelWorx\ExcelConverter;

use LabelWorx\ExcelConverter\Converters\ConvertFromCSV;
use LabelWorx\ExcelConverter\Converters\ConvertFromCustom;
use LabelWorx\ExcelConverter\Converters\ConvertFromTSV;
use LabelWorx\ExcelConverter\Converters\ConvertFromXLS;
use LabelWorx\ExcelConverter\Converters\ConvertFromXLSX;

class ExcelConverter
{
    /**
     * @var string
     */
    private $source;

    /**
     * @var string|int|null
     */
    private $worksheet = null;

    /**
     * @var string
     */
    private $destination;

    /**
     * @var string
     */
    private $file_type;

    /**
     * @var string
     */
    private $source_delimiter;

    /**
     * @var string source_enclosure
     */
    private $source_enclosure;

    /**
     * @var string
     */
    private $destination_delimiter;

    /**
     * @var string
     */
    private $destination_enclosure;

    /**
     * @param $file
     * @param null $delimiter
     * @param null $enclosure
     * @return $this
     * @throws \Exception
     */
    public function source($file, $delimiter = null, $enclosure = null)
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
     * @param $file
     * @return string
     * @throws \Exception
     */
    private function validateSource($file)
    {
        if (! is_string($file)) {
            throw new \Exception('Specified source file should be a string');
        }

        if (is_dir($file)) {
            throw new \Exception('Specified source file is a directory');
        }

        if (! file_exists($file)) {
            throw new \Exception('Specified source does not exist');
        }

        if (! is_readable($file)) {
            throw new \Exception('Specified source file is not readable');
        }

        return $file;
    }

    /**
     * @param string|int|null $sheet
     * @return $this
     */
    public function worksheet($sheet)
    {
        $this->worksheet = $sheet;

        return $this;
    }

    /**
     * @param $destination
     * @param string $delimiter
     * @param string $enclosure
     * @throws \Exception
     */
    public function to($destination, $delimiter = ',', $enclosure = '"')
    {
        $this->destination = $this->checkDestinationFile($destination);
        $this->destination_delimiter = $delimiter;
        $this->destination_enclosure = $enclosure;

        $this->checkIfCanConvert();

        $this->doConversion();
    }

    /**
     * @param $destination
     * @throws \Exception
     */
    public function toCSV($destination)
    {
        $this->to($destination);
    }

    /**
     * @param $destination
     * @param string $enclosure
     * @throws \Exception
     */
    public function toTSV($destination, $enclosure = '"')
    {
        $this->to($destination, "\t", $enclosure);
    }

    public function sourceDelimiter($delimiter)
    {
        $this->source_delimiter = $delimiter;

        return $this;
    }

    /**
     * @param $destination
     * @return mixed
     * @throws \Exception
     */
    private function checkDestinationFile($destination)
    {
        if (is_dir($destination)) {
            throw new \Exception('Destination file is directory');
        }

        if (file_exists($destination)) {
            unlink($destination);
        }

        return $destination;
    }

    /**
     * @throws \Exception
     */
    private function checkIfCanConvert()
    {
        if (! $this->source) {
            throw new \Exception('You did not specify a source file');
        }

        if (! $this->destination) {
            throw new \Exception('You did not specify a destination file');
        }
    }

    private function detectFileType()
    {
        $info = pathinfo($this->source);
        $this->file_type = strtolower($info['extension']);

        if (! array_key_exists($this->file_type, $this->registeredConverters())) {
            throw new \Exception('File type not supported ['.$this->file_type.']');
        }
    }

    private function registeredConverters()
    {
        return [
            'csv' => ConvertFromCSV::class,
            'custom' => ConvertFromCustom::class,
            'tsv' => ConvertFromTSV::class,
            'xls' => ConvertFromXLS::class,
            'xlsx' => ConvertFromXLSX::class,
        ];
    }

    private function doConversion()
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
            $this->worksheet
        );

        $converter->convert();
    }
}
