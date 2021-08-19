<?php

namespace App\Exception;

use Throwable;

class ApiException extends \Exception
{
    public const MESSAGE_FORMAT_ERROR    = 'data format error';
    public const MESSAGE_VALUE_NOT_EMPTY = 'the value cannot be empty';

    public function __construct(string $message = self::MESSAGE_FORMAT_ERROR, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}