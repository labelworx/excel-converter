<?php

namespace LabelWorx\ExcelConverter;

use Illuminate\Support\ServiceProvider;

class ExcelConverterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('lw-excel-converter', function () {
            return new ExcelConverter();
        });
    }
}
