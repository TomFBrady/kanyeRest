<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseJsonRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        if ('application/json' != $this->header('Accept')) {
            abort(response()->json(['message' => 'The request must accept JSON responses.'], 406)); // 406 Not Acceptable
        }
    }
}
