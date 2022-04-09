<?php

namespace Assegai\Database\Management;

use stdClass;

final class FindWhereOptions
{
  public function __construct(public readonly stdClass|array $conditions)
  {
  }
}