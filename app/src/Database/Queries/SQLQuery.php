<?php

namespace LifeRaft\Database\Queries;

use stdClass;

final class SQLQuery
{
  private string $sql;

  public function __construct(
    private \PDO $db,
  ) { }

  public function sql(): string
  {
    return $this->sql;
  }

  public function setSQL(string $sql): void {}

  public function appendSQL(string $tail): void {}

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

  public function execute(): stdClass
  {
    return new stdClass;
  }
}

?>