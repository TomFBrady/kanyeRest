<?php

namespace App\Http\Controllers;

class QuotesController extends Controller
{
    public function index()
    {
        return response()->json('Endpoint hit!');
    }
}
