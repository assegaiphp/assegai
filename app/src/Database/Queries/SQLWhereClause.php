<?php

namespace LifeRaft\Database\Queries;

use LifeRaft\Database\Traits\ExecutableTrait;

final class SQLWhereClause
{
  use ExecutableTrait;

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