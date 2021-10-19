<?php

namespace LifeRaft\Database\Queries;

final class SQLSelectDefinition
{
  public function __construct(
    private SQLQuery $query
  ) {
    $sql = "SELECT ";
    $this->query->setSQL(sql: $sql);
  }

  public function all(array $columns = []): SQLSelectExpression
  {
    $sql = "";
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
    $sql = trim($sql, $separator);

    $this->query->appendSQL($sql);

    return new SQLSelectExpression( query: $this->query );
  }
}

?>