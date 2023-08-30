<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function testDebugging()
    {
        $test1 = 'Hello';
        $test2 = ['Del', 'array', 'trades'];

        dump($test1);
        dump($test2);
    }
}
