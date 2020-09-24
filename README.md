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
$excel->source('path/to/input.xls')->toCSV('path/to/output.csv');
$excel->source('path/to/input.xlsx')->toTSV('path/to/output.tsv');
$excel->source('path/to/input.csv')->toTSV('path/to/output.tsv');
$excel->source('path/to/input.tsv')->toCSV('path/to/output.csv');

// Converts Pipe delimited file to Semi-Colon delimited file by passing delimiters
$excel->source('path/to/input.txt', '|')->to('path/to/output.txt', ';');

// For Excel source files you can specify a worksheet by name or number
$excel->source('path/to/input.xls')->worksheet('Sheet 2')->toCSV('path/to/output.csv');
$excel->source('path/to/input.xlsx')->worksheet(2)->toTSV('path/to/output.tsv');
```

## Laravel Usage
For Laravel users there is th option of using a `Facade`.
```php
use LabelWorx\ExcelConverter\Facades\ExcelConverter;

ExcelConverter::source('path/to/input.xls')->toCSV('path/to/output.csv');
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](./LICENSE.md)
