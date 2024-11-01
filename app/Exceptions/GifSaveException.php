<?php

namespace App\Exceptions;

use Exception;

class GifSaveException extends Exception
{
    public function __construct($message = "Error al guardar el GIF", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
