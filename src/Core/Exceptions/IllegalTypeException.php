<?php

namespace Assegai\Core\Exceptions;

use Exception;

class IllegalTypeException extends Exception
{
  public function __construct(string $expected, string $actual, string $message = 'Illegal type error')
  {
    parent::__construct(message: sprintf("%s: Expected %s instead of %s", $message, $expected, $actual));
  }
}