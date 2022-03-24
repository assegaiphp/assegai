<?php

namespace Assegai\Lib\Authentication\JWT;

use Assegai\Lib\Authentication\AuthToken;
use Assegai\Lib\Authentication\Interfaces\IToken;
use stdClass;

final class JWTToken implements IToken
{
  private string $signature = '';

  public function __construct(
    private JWTHeader $header,
    private JWTPayload $payload,
  ) { }

  public function header(): JWTHeader
  {
    return $this->header;
  }

  public function payload(): JWTPayload
  {
    return $this->payload;
  }

  public function value(): string
  {
    return $this->header . '.' . $this->payload . '.' . $this->signature;
  }

  public function __toString(): string
  {
    return $this->value();
  }

  public static function parse(string $tokenString, bool $returnArray = false): false|stdClass|array
  {
    $segments = explode('.', $tokenString);
    
    if (count($segments) !== 3)
    {
      return false;
    }

    $parts = [];
    for ($x = 0; $x < 2; $x++)
    {
      $parts[] = json_decode(base64_decode($segments[$x]));
    }

    return $returnArray ? $parts : (object) $parts;
  }
}

?>