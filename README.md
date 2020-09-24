# Excel Converter

This package allows you to quickly convert XLSX, XLS or delimited files such as CSV, TSV, Semi Colon, Pipe Delimited or 
files with any bespoke delimiter of your choosing to a delimited file.
 
## Installation

Require the package using composer:

```bash
composer require labelworx/excel-converter
```

## Usage

```php
use LabelWorx\ExcelConverter\ExcelConverter;

$excel = new ExcelConverter();

// Simple Conversions
$excel->source('input.xls')->toCSV('output.csv');
$excel->source('input.xlsx')->toTSV('output.tsv');
$excel->source('input.csv')->toTSV('output.tsv');
$excel->source('input.tsv')->toCSV('output.csv');

// Create File Controls by passing the delimiters
// Converts pipe delimited file to semi-colon delimited
$excel->source('input.txt', '|')->to('output.txt', ';');
```

## Laravel Usage
For Laravel users there is th option of using a `Facade`.
```php
use LabelWorx\ExcelConverter\ExcelConverter;

ExcelConverter::source('input.xls')->toCSV('output.csv');
```


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](./LICENSE.md)
