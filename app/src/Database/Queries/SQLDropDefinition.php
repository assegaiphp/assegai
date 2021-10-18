<?php

namespace LifeRaft\Database\Queries;

final class SQLDropDefinition
{
  public function __construct(
    private SQLQuery $query
  ) { }

  public function database(string $dbName): SQLDropDatabaseStatement
  {
    return new SQLDropDatabaseStatement( query: $this->query, dbName: $dbName );
  }

  public function table(string $tableName): SQLDropTableStatement
  {
    return new SQLDropTableStatement( query: $this->query, tableName: $tableName );
  }
}

?>