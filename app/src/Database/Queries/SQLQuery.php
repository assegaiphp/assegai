<?php

namespace LifeRaft\Database\Queries;

use stdClass;

final class SQLQuery
{
  private string $sql;

  public function __construct(
    private \PDO $db,
  ) {
    $this->sql = '';
  }

  public function __toString()
  {
    return $this->sql;
  }

  public function sql(): string
  {
    return $this->sql;
  }

  public function setSQL(string $sql): void
  {
    $this->sql = $sql;
  }

  public function appendSQL(string $tail): void
  {
    $this->sql = trim($this->sql) . " $tail";
  }

  public function create(): SQLCreateDefinition
  {
    return new SQLCreateDefinition( query: $this );
  }

  public function insert(): SQLQuery
  {
    return $this;
  }

  public function update(): SQLQuery
  {
    return $this;
  }

  public function select( array $columns = [] ): SQLSelectExpression
  {
    $expression = new SQLSelectExpression( query: $this );
    return $expression;
  }

  public function delete(): SQLQuery
  {
    return $this;
  }

  public function execute(): SQLQueryResult
  {
    return new SQLQueryResult( data: [], errors: [], isOK: false );
  }
}

?>