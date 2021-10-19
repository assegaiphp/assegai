<?php

namespace LifeRaft\Database\Queries;

final class SQLInsertIntoDefinition
{
  public function __construct(
    private SQLQuery $query,
    private string $tableName
  )
  {
    $queryString = "INSERT INTO `$tableName` ";

    $this->query->setQueryString($queryString);
  }

  public function singleRow(array $columns = []): SQLInsertIntoStatement
  {
    return new SQLInsertIntoStatement(
      query: $this->query,
      columns: $columns
    );
  }

  public function multipleRows(array $columns = []): SQLInsertIntoMultipleStatement
  {
    return new SQLInsertIntoMultipleStatement(
      query: $this->query,
      columns: $columns
    );
  }
}


?>