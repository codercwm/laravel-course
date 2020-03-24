<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class Char extends Facade{
    protected static function getFacadeAccessor() {
        return 'char';
    }
}