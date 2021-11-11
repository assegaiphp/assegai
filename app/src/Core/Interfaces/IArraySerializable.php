<?php

namespace Assegai\Core\Interfaces;

interface IArraySerializable
{
  public function toArray(): array;

  public function fromArray(array $array): mixed;
}

?>