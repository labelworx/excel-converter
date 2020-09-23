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
    protected $delimiter;

    /**
     * @var string
     */
    protected $enclosure;

    /**
     * BaseConverter constructor.
     * @param $source
     * @param $destination
     * @param $delimiter
     * @param $enclosure
     */
    public function __construct($source, $destination, $delimiter, $enclosure)
    {
        $this->source = $source;
        $this->destination = $destination;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
    }

    abstract public function convert();
}
