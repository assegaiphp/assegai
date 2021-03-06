<?php

namespace Assegai\Database\Queries;

use Assegai\Database\Traits\ExecutableTrait;

final class SQLLimitClause
{
  use ExecutableTrait;

  public function __construct(
    private SQLQuery $query,
    private int $limit,
    private ?int $offset = null,
  )
  {
    $queryString = "LIMIT " . (!is_null($offset) ? "$offset,$limit" : "$limit");
    $this->query->appendQueryString($queryString);
  }
}

?>