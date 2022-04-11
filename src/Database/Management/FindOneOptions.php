<?php

namespace Assegai\Database\Management;

use Assegai\Database\Queries\FindOptions;
use stdClass;

final class FindOneOptions extends FindOptions
{
  public function __construct(
    public readonly null|stdClass|array $select = null,
    public readonly null|stdClass|array $relations = null,
    public readonly ?FindWhereOptions $where = null,
    public readonly null|stdClass|array $order = null,
    public readonly ?int $skip = null,
    public readonly ?int $limit = null,
  ) { }
}