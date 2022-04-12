<?php

namespace Assegai\Core\Exceptions;

use Exception;

class SaveException extends Exception
{
  public function __construct(string $details)
  {
    parent::__construct(message: sprintf("A save error occured: %s", $details));
  }
}