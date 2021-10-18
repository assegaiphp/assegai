<?php

namespace LifeRaft\Database\Queries;

use stdClass;

final class SQLQuery
{
  private string $sql = '';
  private string $type = '';
  private array $params = [];

  public function __construct(
    private \PDO $db,
    private string $fetchClass = stdClass::class,
    private int $fetchMode = \PDO::FETCH_ASSOC,
    private array $fetchClassParams = []
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

  public function type(): string
  {
    return $this->type;
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
    $this->type = SQLQueryType::CREATE;
    return new SQLCreateDefinition( query: $this );
  }

  public function drop(): SQLDropDefinition
  {
    $this->type = SQLQueryType::DROP;
    return new SQLDropDefinition( query: $this );
  }

  public function rename(): SQLRenameStatement
  {
    return new SQLRenameStatement( query: $this );
  }

  public function use(string $dbName): SQLUseStatement
  {
    $this->type = SQLQueryType::USE;
    return new SQLUseStatement( query: $this, dbName: $dbName );
  }

  public function describe(string $subject): SQLDescribeStatement
  {
    $this->type = SQLQueryType::DESCRIBE;
    return new SQLDescribeStatement( query: $this, subject: $subject );
  }

  public function insertInto(string $tableName, array $columns = []): SQLInsertIntoStatement
  {
    $this->type = SQLQueryType::INSERT;
    return new SQLInsertIntoStatement( query: $this, tableName: $tableName, columns: $columns );
  }

  public function update(): SQLQuery
  {
    $this->type = SQLQueryType::UPDATE;
    return $this;
  }

  public function select( array $columns = [] ): SQLSelectExpression
  {
    $this->type = SQLQueryType::SELECT;
    $expression = new SQLSelectExpression( query: $this );
    return $expression;
  }

  public function delete(): SQLQuery
  {
    $this->type = SQLQueryType::DELETE;
    return $this;
  }

  public function execute(): SQLQueryResult
  {
    try
    {
      $statement = $this->db->prepare($this->sql);

      switch ($this->type())
      {
        default: $statement->execute( $this->params );
      };

      if (!empty($statement->errorInfo()))
      {
        if (!empty($this->params))
        {
          call_user_func_array([$statement, 'setFetchMode'], $this->fetchClassParams );
        }
  
        $data = match ($this->type()) {
          SQLQueryType::SELECT => $statement->fetchAll(),
          default => $statement->fetchAll()
        };
  
        return new SQLQueryResult(data: $data, errors: [], isOK: true);
      }

      $errors = [
        'code' => $this->db->errorCode(),
        'info' => $this->db->errorInfo(),
      ];

      return new SQLQueryResult( data: [], errors: $errors, isOK: false );
    }
    catch (\Exception $e)
    {
      die($e->getMessage());
    }
  }
}

?>