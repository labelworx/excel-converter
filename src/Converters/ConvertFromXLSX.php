<?php

namespace LabelWorx\ExcelConverter\Converters;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use DateTime;

class ConvertFromXLSX extends BaseConverter
{
    public function convert()
    {
        $reader = ReaderEntityFactory::createReaderFromFile($this->source);

        $reader->open($this->source);

        $sheet = $this->getWorksheet($reader);

        $this->processSheet($sheet);

        $reader->close();
    }

    private function processSheet($sheet)
    {
        $handle = fopen($this->destination, 'w');

        foreach ($sheet->getRowIterator() as $row) {
            $rowData = $this->format($row->toArray());
            $rowData = $this->handleCharacterEncoding($rowData);
            $rowData = $this->pruneEmptyLastCell($rowData);

            if ($this->destination_enclosure == '') {
                fwrite($handle, implode($this->destination_delimiter, $rowData) . "\n");
            } else {
                fputcsv($handle, $rowData, $this->destination_delimiter, $this->destination_enclosure);
            }
        }

        fclose($handle);
    }

    private function handleCharacterEncoding($rowData)
    {
        return array_map(
            function ($arg) {
                return mb_convert_encoding($arg, 'UTF-8', mb_detect_encoding($arg));
            },
            $rowData
        );
    }

    private function pruneEmptyLastCell($rowData)
    {
        $count = count($rowData) - 1;

        if ($rowData[$count] == '') {
            unset($rowData[$count]);
        }

        return $rowData;
    }

    /**
     * @param $reader
     * @return mixed
     * @throws \Exception
     */
    private function getWorksheet($reader)
    {
        $count = 1;
        foreach ($reader->getSheetIterator() as $sheet) {
            if (is_null($this->worksheet)) {
                return $sheet;
            }

            if ($sheetName = $sheet->getName() == $this->worksheet) {
                return $sheet;
            }

            if ($count == $this->worksheet) {
                return $sheet;
            }

            $count++;
        }

        throw new \Exception("Worksheet not found [{$this->worksheet}]");
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
