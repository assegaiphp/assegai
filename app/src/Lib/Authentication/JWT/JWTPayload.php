<?php

namespace Assegai\Lib\Authentication\JWT;

use DateTime;
use Assegai\Core\Config;

final class JWTPayload
{
  private int $iat = 0;
  private int $exp = 0;
  private string $jti = '';

  public function __construct(
    private ?string $aud = null,
    private ?string $iss = null,
    private ?string $sub = null,
    private ?string $lifespan = '1 hour',
  )
  {
    $dateTime = new DateTime;

    $this->iat = $dateTime->getTimestamp();
    if (isset(Config::get('authentication')['jwt']))
    {
      extract(Config::get('authentication')['jwt']);

      $exp = isset($lifespan)
        ? strtotime(datetime: $lifespan)
        : strtotime(datetime: '1 hour');

      $this->exp = (is_bool($exp)) ? strtotime(datetime: '1 hour') : $exp;

      if (is_null($this->iss))
      {
        $this->iss = isset($issuer) ? $issuer : 'assegai'; 
      }

      if (is_null($this->aud))
      {
        $this->aud = isset($audience) ? $audience : 'https://yourdomain.com'; 
      }

      if (is_null($this->sub))
      {
        $this->sub = '0'; 
      }
    }

    if (is_null($this->exp))
    {
      $this->exp = strtotime(datetime: '1 hour');
    }
  }

  public function toArray(): array
  {
    return [
      'iat' => $this->iat,
      'exp' => $this->exp,
      'aud' => $this->aud,
      'iss' => $this->iss,
      'sub' => $this->sub,
      'jti' => $this->jti,
    ];
  }

  public function toJSON(): string
  {
    return json_encode(value: $this->toArray());
  }

  public function __toString(): string
  {
    return base64_encode(string: $this->toJSON());
  }

}

?>