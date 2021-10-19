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
    $this->query->appendQueryString("HAVING $condition");
  }

  public function or(string $condition): SQLHavingClause
  {
    $this->query->appendQueryString("OR $condition");
    return $this;
  }

  public function and(string $condition): SQLHavingClause
  {
    $this->query->appendQueryString("AND $condition");
    return $this;
  }
}

?>