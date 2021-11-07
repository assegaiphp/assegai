<?php

namespace LifeRaft\Lib\Authentication;

use LifeRaft\Core\Responses\NotImplementedErrorResponse;
use LifeRaft\Lib\Authentication\Interfaces\IToken;

class AuthToken implements IToken
{
  public function __construct()
  {
  }

  public function value(): string
  {
    exit(new NotImplementedErrorResponse(message: get_called_class()));
    return '';
  }
}

?>