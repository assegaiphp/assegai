<?php

namespace Assegai\Core\Exceptions;

class NotImplmentedException extends AssegaiException
{
  public function __construct(string $message = "Feature Not Implemented", ?Throwable $previous = null)
  {
    parent::__construct($message, self::EXTYPE_NOT_IMPLEMENTED, $previous);
  }
}