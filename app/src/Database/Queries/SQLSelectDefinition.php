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
      $queryString .= "*";
    }
    else
    {
      foreach ($columns as $key => $value)
      {
        $queryString .= is_numeric($key) ? "${value}${separator}" : "$value as ${key}${separator}";
      }
    }
    $queryString = trim($queryString, $separator);

    $this->query->appendQueryString($queryString);

    return new SQLSelectExpression( query: $this->query );
  }
}

?>