<?php

namespace LifeRaft\Database\Queries;

final class SQLCreateDefinition
{
  public function __construct(
    protected SQLQuery $query
  )
  {
  }

  public function table(string $name): SQLTableOptions
  {
    $this->query->appendSQL("TABLE $name");
    return new SQLTableOptions( query: $this->query );
  }
  
  public function database(string $name): mixed
  {
    return null;
  }

  public function execute(): SQLQueryResult
  {
    return $this->query->execute();
  }
}

?>