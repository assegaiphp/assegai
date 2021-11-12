<?php

namespace Assegai\Core\Interfaces;

interface IJSONSerializable
{
  public function toJSON(): string;

  public function fromJSON(string $jsonString): mixed;
}

