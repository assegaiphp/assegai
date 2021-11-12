<?php

namespace Assegai\Core\Responses;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class ResponseType
{
  public function __construct(
    private string $type = 'text/plain'
  ) {}

  public function __toString(): string
  {
    return $this->type;
  }

  #[ArrayShape(['type' => "string"])]
  public function __serialize(): array
  {
    return ['type' => strval($this)];
  }

  public function __unserialize(array $data): void
  {
    if (isset($data['type']))
    {
      $this->type = $data['type'];
    }
  }

  #[Pure]
  public static function TEXT(string $type): ResponseType
  {
    return new ResponseType("text/$type");
  }

  #[Pure]
  public static function JSON(): ResponseType
  {
    return new ResponseType('application/json');
  }

  #[Pure]
  public static function HTML(): ResponseType
  {
    return ResponseType::TEXT('html');
  }

  #[Pure]
  public static function PLAIN(): ResponseType
  {
    return ResponseType::TEXT('plain');
  }

  #[Pure]
  public static function XML(): ResponseType
  {
    return ResponseType::TEXT('xml');
  }

  #[Pure]
  public static function CSS(): ResponseType
  {
    return ResponseType::TEXT('css');
  }

  #[Pure]
  public static function CSV(): ResponseType
  {
    return ResponseType::TEXT('csv');
  }

  #[Pure]
  public static function JAVASCRIPT(): ResponseType
  {
    return ResponseType::TEXT('javascript');
  }
}

