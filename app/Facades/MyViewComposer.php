<?php
namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class MyViewComposer extends Facade {

    protected static function getFacadeAccessor() { return 'myviewcomposera'; }

}