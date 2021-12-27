<?php

use Assegai\Lib\Authentication\AuthToken;
use PHPUnit\Framework\TestCase;

final class AuthenticationTest extends TestCase
{
  public function testCreateAuthToken(): void {
    $token = new AuthToken();
    $this->assertIsString($token->value());
  }

  public function testVerifyAuthToken(): void {}
}

