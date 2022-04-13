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
    if (!empty($condition))
    {
      $this->query->appendQueryString("WHERE $condition");
    }
  }

  public function or(string $condition): SQLWhereClause
  {
    $operator = $this->filterOperator('OR');
    $this->query->appendQueryString("$operator $condition");
    return $this;
  }

  public function and(string $condition): SQLWhereClause
  {
    $operator = $this->filterOperator('AND');
    $this->query->appendQueryString("$operator $condition");
    return $this;
  }

  private function filterOperator(string $operator): string
  {
    return str_contains((string)$this->query, 'WHERE') ? $operator : 'WHERE';
  }
}

?>