<?php

namespace Assegai\Core\Exceptions;

use Exception;

class EmptyCriteriaException extends Exception
{
  public function __construct(string $methodName)
  {
    $message = 'Empty criteria(s) are not allowed for the %s method';

    parent::__construct(message: sprintf($message, $methodName));
  }
}