<?php

namespace Assegai\Database\Queries;

use Assegai\Database\Traits\ExecutableTrait;
use Assegai\Database\Traits\JoinableTrait;

final class SQLJoinSpecification
{
  use ExecutableTrait;
  use JoinableTrait;

  public function __construct(
    private SQLQuery $query,
    private string|array $conditionOrList,
    private bool $isUsing = false
  ) {
    $specification =
      is_array($conditionOrList)
      ? '(' . implode(',', $conditionOrList) . ')'
      : $conditionOrList;

    $queryString = $isUsing ? "USING $specification" : "ON $specification";
    $this->query->appendQueryString(tail: $queryString);
  }

  public function where(string $condition): SQLWhereClause
  {
    return new SQLWhereClause(query: $this->query, condition: $condition);
  }
}

?>