<?php

namespace Assegai\Database;

use stdClass;

final class FindOptions
{
  public function __construct(
    public readonly null|stdClass|array $select = null,
    public readonly null|stdClass|array $relations = null,
    public readonly null|stdClass|array $where = null,
    public readonly null|stdClass|array $order = null,
    public readonly ?int $skip = null,
    public readonly ?int $limit = null,
  ) { }
}