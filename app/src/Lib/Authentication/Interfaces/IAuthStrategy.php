<?php

namespace Assegai\Lib\Authentication\Interfaces;

interface IAuthStrategy
{
  public function authenticate(mixed $data, mixed $params): mixed;
}

?>