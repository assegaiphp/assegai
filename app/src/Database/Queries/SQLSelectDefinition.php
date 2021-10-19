<?php

namespace LifeRaft\Database\Queries;

final class SQLSelectDefinition
{
  public function __construct(
    private SQLQuery $query
  ) {
    $queryString = "SELECT ";
    $this->query->setQueryString(queryString: $queryString);
  }

  public function all(array $columns = []): SQLSelectExpression
  {
    $queryString = "";
    $separator = ', ';

    if (empty($columns))
    {
      $sql .= "*";
    }
    else
    {
      foreach ($columns as $key => $value)
      {
        $sql .= is_numeric($key) ? "${value}${separator}" : "$value as ${key}${separator}";
      }
    }
    $queryString = trim($sql, $separator);

    $this->query->appendQueryString($sql);

    return new SQLSelectExpression( query: $this->query );
  }
}

?>