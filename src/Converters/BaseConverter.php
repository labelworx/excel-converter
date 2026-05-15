<?php

namespace LabelWorx\ExcelConverter\Converters;

abstract class BaseConverter
{
    public function __construct(
        protected readonly string $source,
        protected readonly string $destination,
        protected readonly string $destination_delimiter,
        protected readonly string $destination_enclosure,
        protected readonly string $source_delimiter,
        protected readonly string $source_enclosure,
        protected readonly int|string|null $worksheet,
        protected readonly string $date_format,
    ) {}

    abstract public function convert(): void;
}
