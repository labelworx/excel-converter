<?php

namespace LabelWorx\ExcelConverter;

use Illuminate\Support\ServiceProvider;

class ExcelConverterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->singleton('lw-excel-converter', function () {
            return new ExcelConverter();
        });
    }
}
