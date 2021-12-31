<?php

namespace Assegai\Lib\Authentication;

use Assegai\Core\Responses\NotImplementedErrorResponse;
use Assegai\Lib\Authentication\Interfaces\IToken;

class AuthToken implements IToken
{
  public function __construct()
  {
  }

  public function value(): string
  {
    exit(new NotImplementedErrorResponse(message: 'Not Implemented: ' . get_called_class()));
    return '';
  }
}

?>