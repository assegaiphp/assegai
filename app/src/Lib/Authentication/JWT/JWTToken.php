<?php

namespace Assegai\Lib\Authentication\JWT;

use Assegai\Lib\Authentication\Interfaces\IToken;

final class JWTToken implements IToken
{
  private string $signature = '';

  public function __construct(
    private JWTHeader $header,
    private JWTPayload $payload,
  )
  {
  }

  public function value(): string
  {
    return $this->header . '.' . $this->payload . '.' . $this->signature;
  }

  public function __toString(): string
  {
    return $this->value();
  }
}

?>