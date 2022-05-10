<?php

namespace Assegai\Core\Exceptions;

use Assegai\Core\Interfaces\INotFoundException;
use Exception;

/**
 * Exception thrown if a runtime Class name check error occurs.
 */
class ClassNotFoundException extends Exception implements INotFoundException
{
  public function __construct(string $className, string $message = 'Class Not Found')
  {
    parent::__construct(message: sprintf("%s: %s", $className, $message), code: 0);
  }
}
