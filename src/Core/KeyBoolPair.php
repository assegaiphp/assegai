<?php

namespace Assegai\Core;

final class KeyBoolPay
{
  public function __construct(
    public readonly string $key,
    public readonly bool $value
  ) { }

  public function toArray(): array
  {
    return ['key' => $this->key, 'value' => $this->value ];
  }

  public function toJSON(): string
  {
    return json_encode($this->toArray());
  }

  public function __serialize(): array
  {
    return $this->toArray(); 
  }

  public function __toString(): string
  {
    return $this->toJSON();
  }
}