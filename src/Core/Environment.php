<?php

namespace Assegai\Core;

use Assegai\Core\Enumerations\EnvironmentType;

final class Environment
{
  public static function is(EnvironmentType $environmentType): bool
  {
    $env = Config::environment('environment');

    return $env === $environmentType->value;
  }

  public static function isLocal(): bool
  {
    return self::is(EnvironmentType::LOCAL);
  }

  public static function isDevelop(): bool
  {
    return self::is(EnvironmentType::DEVELOP);
  }

  public static function isQA(): bool
  {
    return self::is(EnvironmentType::QA);
  }

  public static function isStaging(): bool
  {
    return self::is(EnvironmentType::STAGING);
  }

  public static function isProd(): bool
  {
    return self::is(EnvironmentType::PRODUCTION);
  }
}