<?php

use Assegai\Lib\Authentication\AuthToken;
use Assegai\Lib\Authentication\JWT\JWTHeader;
use Assegai\Lib\Authentication\JWT\JWTPayload;
use Assegai\Lib\Authentication\JWT\JWTToken;
use PHPUnit\Framework\TestCase;

final class AuthenticationTest extends TestCase
{
  public function testCreateAuthToken(): void
  {
    $token = new AuthToken();
    $this->assertIsString($token->value());
  }

  public function testVerifyAuthToken(): void {}

  public function testCreateJwtHeader(): void
  {
    $header = new JWTHeader();
    $this->assertNotNull(actual: $header);
    $this->assertIsArray(actual: $header->toArray());
    $this->assertIsString(actual: $header->toJSON());
    $this->assertIsString(actual: strval($header));
  }

  public function testCreateJwtPayload(): void
  {
    $payload = new JWTPayload();
    $this->assertNotNull(actual: $payload);
    $this->assertIsArray(actual: $payload->toArray());
    $this->assertIsString(actual: $payload->toJSON());
    $this->assertIsString(actual: strval($payload));
  }

  public function testCreateJwtToken(): void
  {
    $token = new JWTToken(
      header: new JWTHeader(),
      payload: new JWTPayload()
    );
    $this->assertNotNull(actual: $token);
    $this->assertIsString(actual: $token->value());
  }
}

