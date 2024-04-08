<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AsciiTextController extends Controller
{
public function __construct() {
    $this->a = array(
        array(' ',' ','_', '_', '_', '', ' '),
        array(' ','/', ' ', ' ', ' ', '\\',' '),
        array('/', ' ', '/', chr(238), '\\', ' ', ),
        array('', '', '', '', '', '', ''),
        array('', '', '', '', '', '', ''),
        array('', '', '', '', '', '', ''),
        array('', '', '', '', '', '', ''),
    );
}


    function index($rq){
        $text='AAA';


    }


    function PrimeraLinea(){

    }

    function SegundaLina


}
