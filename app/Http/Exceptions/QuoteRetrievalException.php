<?php

namespace App\Http\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuoteRetrievalException extends \Exception
{
    protected $message = 'Quote retrieval failed';
    protected $code = 404;

    public function __construct($message = 'Quote retrieval failed', $code = 500, ?\Exception $previous = null)
    {
        $this->message = $message;
        $this->code = $code;
        parent::__construct($message, $code, $previous);
    }

    public function render(Request $request): Response
    {
        return response(['error' => $this->message], $this->code);
    }
}
