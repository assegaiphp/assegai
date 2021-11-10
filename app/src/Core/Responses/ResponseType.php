<?php

namespace Assegai\Core\Responses;

class ResponseType
{
  public function __construct(
    private string $type = 'text/plain'
  ) {}

  public function __toString(): string
  {
    return $this->type;
  }

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

  public static function TEXT(string $type): ResponseType
  {
    return new ResponseType("text/{$type}");
  }

  public static function JSON(): ResponseType
  {
    return new ResponseType('application/json');
  }

  public static function HTML(): ResponseType
  {
    return ResponseType::TEXT('html');
  }

  public static function PLAIN(): ResponseType
  {
    return ResponseType::TEXT('plain');
  }

  public static function XML(): ResponseType
  {
    return ResponseType::TEXT('xml');
  }

  public static function CSS(): ResponseType
  {
    return ResponseType::TEXT('css');
  }

  public static function CSV(): ResponseType
  {
    return ResponseType::TEXT('csv');
  }

  public static function JAVASCRIPT(): ResponseType
  {
    return ResponseType::TEXT('javascript');
  }
}

?>