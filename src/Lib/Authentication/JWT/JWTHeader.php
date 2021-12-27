<?php

namespace Assegai\Lib\Authentication\JWT;

use JetBrains\PhpStorm\ArrayShape;

final class JWTHeader
{
  const ALG_HS256 = 'HS256';
  const TYP_JWT = 'JWT';

  public function __construct(
    private string $alg = JWTHeader::ALG_HS256,
    private string $typ = JWTHeader::TYP_JWT,
  ) { }

  #[ArrayShape(['alg' => "string", 'typ' => "string"])]
  public function toArray(): array
  {
    return ['alg' => $this->alg, 'typ' => $this->typ];
  }

  public function toJSON(): string
  {
    return json_encode(value: $this->toArray());
  }

  public function __toString(): string
  {
    return base64_encode($this->toJSON());
  }
}

