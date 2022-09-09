<?php

namespace LabelWorx\ExcelConverter\Converters;

class ConvertFromTSV extends BaseConverter
{
    public function convert(): void
    {
        $source_handle = fopen($this->source, 'rb');
        $destination_handle = fopen($this->destination, 'wb');

        while (($row = fgetcsv($source_handle, 0, "\t")) !== false) {
            fputcsv($destination_handle, $row, $this->destination_delimiter, $this->destination_enclosure);
        }

        fclose($source_handle);
        fclose($destination_handle);
    }
}
