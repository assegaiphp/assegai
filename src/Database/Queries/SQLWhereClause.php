<?php

namespace Assegai\Database\Queries;

use Assegai\Database\Traits\ExecutableTrait;
use Assegai\Database\Traits\SQLAggregatorTrait;

final class SQLWhereClause
{
  use ExecutableTrait;
  use SQLAggregatorTrait;

  public function __construct(
    private SQLQuery $query,
    private string $condition
  )
  {
    $this->query->appendQueryString("WHERE $condition");
  }

  public function or(string $condition): SQLWhereClause
  {
    $this->query->appendQueryString("OR $condition");
    return $this;
  }

  public function and(string $condition): SQLWhereClause
  {
    $this->query->appendQueryString("AND $condition");
    return $this;
  }
}

?>