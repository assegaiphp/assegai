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
    # TODO: #32 Implement AuthToken::value
    exit(new NotImplementedErrorResponse(message: get_called_class()));
    return '';
  }
}

?>