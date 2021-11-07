<?php

namespace LifeRaft\Lib\Authentication\JWT;

use LifeRaft\Lib\Authentication\Interfaces\IToken;

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
}

?>