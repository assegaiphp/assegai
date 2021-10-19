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
    $this->query->appendSQL("WHERE $condition");
  }

  public function or(string $condition): SQLWhereClause
  {
    $this->query->appendSQL("OR $condition");
    return $this;
  }

  public function and(string $condition): SQLWhereClause
  {
    $this->query->appendSQL("AND $condition");
    return $this;
  }
}

?>