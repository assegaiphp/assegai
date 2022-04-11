<?php

namespace Assegai\Database\Management;

use stdClass;

final class FindWhereOptions
{
  public function __construct(public readonly stdClass|array $conditions)
  {
  }

  public function __toString(): string
  {
    $output = '';

    foreach ($this->conditions as $key => $value)
    {
      $output .= "$key=$value";
    }

    return $output;
  }
}