<?php

namespace Assegai\Util;

final class TextFormatter
{
  public static function toCamelCase(...$words): string
  {
    $result = '';
    $buffer = '';

    foreach ($words as $index => $word)
    {
      $buffer = strtolower($word);
      $result .= $index === 0 ? $buffer : (strtoupper(substr($buffer, 0, 1)) . substr($buffer, 1));
    }

    return $result;
  }
}