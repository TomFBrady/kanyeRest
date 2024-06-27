<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuotesController extends Controller
{
    public function index()
    {
        return response()->json('Endpoint hit!');
    }
}
