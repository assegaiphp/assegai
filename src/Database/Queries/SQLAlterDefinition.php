<?php

namespace Assegai\Database\Queries;

final class SQLAlterDefinition
{
  public function __construct(
    private SQLQuery $query
  )
  {
  }

  public function database(): mixed
  {}

  public function table(string $tableName): SQLAlterTableOption
  {
    $this->query->setQueryString(queryString: "ALTER TABLE `$tableName`");
    return new SQLAlterTableOption( query: $this->query );
  }
}

?>