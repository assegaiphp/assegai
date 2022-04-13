<?php

namespace Assegai\Core\Exceptions;

use Exception;

/**
 * Exception thrown if a runtime Class name check error occurs.
 */
class ClassNotFoundException extends Exception
{
  public function __construct(string $className, string $message = 'Class Not Found')
  {
    parent::__construct(message: sprintf("%s: %s", $className, $message), code: 0);
  }
}
