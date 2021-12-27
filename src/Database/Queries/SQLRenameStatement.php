<?php

namespace Assegai\Database\Queries;

final class SQLRenameStatement
{
  public function __construct(
    private SQLQuery $query,
  ) { }

  // public function database(string $from, string $to): SQLRenameDatabaseStatement
  // {
  //   return new SQLRenameDatabaseStatement( query: $this->query, oldDbName: $from, newDbName: $to );
  // }

  public function table(string $from, string $to): SQLRenameTableStatement
  {
    return new SQLRenameTableStatement( query: $this->query, oldTableName: $from, newTableName: $to );
  }
}

?>