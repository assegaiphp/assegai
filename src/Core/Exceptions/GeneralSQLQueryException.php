<?php

namespace Assegai\Core\Exceptions;

use Exception;

class GeneralSQLQueryException extends Exception
{
  public function __construct(string $message = 'An error occured while executing an SQLQuery')
  {
    parent::__construct(message: sprintf("General SQLQuery error: %s", $message));
  }
}