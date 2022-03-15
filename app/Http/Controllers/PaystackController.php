<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaystackController extends Controller
{
    public function initialize()
    {
        # code...
    }

    public function callback()
    {
        return view('success');
    }
}