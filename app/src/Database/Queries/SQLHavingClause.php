<?php

namespace LifeRaft\Database\Queries;

use LifeRaft\Database\Traits\ExecutableTrait;

final class SQLHavingClause
{
  use ExecutableTrait;

  public function __construct(
    private SQLQuery $query,
    private string $condition
  )
  {
    $this->query->appendSQL("HAVING $condition");
  }

  public function or(string $condition): SQLHavingClause
  {
    $this->query->appendSQL("OR $condition");
    return $this;
  }

  public function and(string $condition): SQLHavingClause
  {
    $this->query->appendSQL("AND $condition");
    return $this;
  }
}

?>