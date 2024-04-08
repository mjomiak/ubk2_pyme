<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;

class MigasController extends Controller
{
    public function render(){

        $nombreRuta = Route::currentRouteName();
        echo $nombreRuta;
        return 'fuck yeah';
    
    }
}
