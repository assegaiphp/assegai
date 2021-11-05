<?php

namespace LifeRaft\Lib\Authentication\Interfaces;

interface IAuthStrategy
{
  public function authenticate(mixed $data, mixed $params): mixed;
}

?>