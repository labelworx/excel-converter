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
     * BaseConverter constructor.
     * @param $source
     * @param $destination
     * @param $delimiter
     * @param $enclosure
     * @param $source_delimiter
     * @param $source_enclosure
     */
    public function __construct($source, $destination, $delimiter, $enclosure, $source_delimiter, $source_enclosure)
    {
        $this->source = $source;
        $this->destination = $destination;
        $this->destination_delimiter = $delimiter;
        $this->destination_enclosure = $enclosure;
        $this->source_delimiter = $source_delimiter;
        $this->source_enclosure = $source_enclosure;
    }

    abstract public function convert();
}
