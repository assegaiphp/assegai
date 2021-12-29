<?php

namespace Assegai\Database\Queries;

use Assegai\Database\Types\JoinType;

final class SQLJoinExpression
{
  public function __construct(
    private SQLQuery $query,
    private array|string $joinTableReferences,
    private ?JoinType $joinType = null
  ) {
    if (is_null($this->joinType))
    {
      $this->joinType = JoinType::JOIN;
    }

    $queryString = match($this->joinType) {
      JoinType::LEFT_JOIN => "LEFT JOIN $joinTableReferences",
      JoinType::RIGHT_JOIN => "RIGHT JOIN $joinTableReferences",
      JoinType::INNER_JOIN => "INNER JOIN $joinTableReferences",
      JoinType::OUTER_JOIN => "OUTER JOIN $joinTableReferences",
      default => "JOIN $joinTableReferences"
    };

    $this->query->appendQueryString(tail: $queryString);
  }

  public function on(string $searchCondition): SQLJoinSpecification
  {
    return new SQLJoinSpecification(query: $this->query, conditionOrList: $searchCondition);
  }

  public function using(array $joinColumnList): SQLJoinSpecification
  {
    return new SQLJoinSpecification(query: $this->query, conditionOrList: $joinColumnList, isUsing: true);
  }
}

?>