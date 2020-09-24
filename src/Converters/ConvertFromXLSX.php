<?php

namespace LabelWorx\ExcelConverter\Converters;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use DateTime;

class ConvertFromXLSX extends BaseConverter
{
    public function convert()
    {
        $handle = fopen($this->destination, 'w');

        $reader = ReaderEntityFactory::createReaderFromFile($this->source);

        $reader->open($this->source);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rowData = $this->format($row->toArray());

                $rowData = array_map(
                    function ($arg) {
                        return mb_convert_encoding($arg, 'UTF-8', mb_detect_encoding($arg));
                    },
                    $rowData
                );

                if (trim(end($rowData)) == '') {
                    array_pop($rowData);
                }

                fputcsv($handle, $rowData, $this->destination_delimiter, $this->destination_enclosure);
            }
        }

        fclose($handle);

        $reader->close();
    }

    /**
     * @param array $row
     * @return array
     */
    private function format(array $row)
    {
        $result = [];
        foreach ($row as $element) {
            if (is_string($element)) {
                $result[] = (string) $element;
                continue;
            }

            if (is_int($element)) {
                $result[] = (string) $element;
                continue;
            }

            if (is_float($element)) {
                $result[] = (float) $element;
                continue;
            }

            if (is_bool($element)) {
                $result[] = $element ? 1 : 0;
                continue;
            }

            if (is_object($element)) {
                if ($element instanceof DateTime) {
                    $result[] = $element->format('Y-m-d H:i:s');
                } else {
                    exit('Element of Type '.get_class($element).'  discovered.');
                }
            }
        }

        return array_map('trim', $result);
    }
}
