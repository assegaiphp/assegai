<?php

namespace Assegai\Core\Exceptions;

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Pure;

class AssegaiException extends \Exception
{
  const EXTYPE_NOT_IMPLEMENTED = 10000;

  #[Pure] public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}