<?php

namespace LabelWorx\ExcelConverter\Converters;

abstract class BaseConverter
{
    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    protected $destination;

    /**
     * @var string
     */
    protected $destination_delimiter;

    /**
     * @var string
     */
    protected $destination_enclosure;

    /**
     * @var string
     */
    protected $source_delimiter;

    /**
     * @var string
     */
    protected $source_enclosure;

    /**
     * @var int|string|null
     */
    protected $worksheet;

    /**
     * @var string
     */
    protected $date_format;

    /**
     * BaseConverter constructor.
     *
     * @param $source
     * @param $destination
     * @param $delimiter
     * @param $enclosure
     * @param $source_delimiter
     * @param $source_enclosure
     * @param $worksheet
     * @param $date_format
     */
    public function __construct($source, $destination, $delimiter, $enclosure, $source_delimiter, $source_enclosure, $worksheet, $date_format)
    {
        $this->source = $source;
        $this->destination = $destination;
        $this->destination_delimiter = $delimiter;
        $this->destination_enclosure = $enclosure;
        $this->source_delimiter = $source_delimiter;
        $this->source_enclosure = $source_enclosure;
        $this->worksheet = $worksheet;
        $this->date_format = $date_format;
    }

    abstract public function convert(): void;
}
