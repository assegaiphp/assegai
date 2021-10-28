<?php

namespace LifeRaft\Database\Queries;

use LifeRaft\Core\Config;
use LifeRaft\Core\Responses\BadRequestErrorResponse;
use LifeRaft\Core\Responses\ConflictErrorResponse;
use stdClass;

final class SQLQuery
{
  private string $queryString;
  private string $type;
  private array $params;
  private ?int $lastInsertId = null;

  public function __construct(
    private \PDO $db,
    private string $fetchClass = stdClass::class,
    private int $fetchMode = \PDO::FETCH_ASSOC,
    private array $fetchClassParams = [],
    private array $passwordHashFields = ['password'],
    private string $passwordHashAlgorithm = ''
  ) {
    if (empty($this->passwordHashAlgorithm))
    {
      $this->passwordHashAlgorithm = Config::get('default_password_hash_algo');

      if (empty($this->passwordHashAlgorithm))
      {
        $this->passwordHashAlgorithm = PASSWORD_DEFAULT;
      }  
    }
    $this->init();
  }

  public function init(): void
  {
    $this->queryString = '';
    $this->type = '';
    $this->params = [];
  }

  public function passwordHashFields(): array
  {
    return $this->passwordHashFields;
  }

  public function passwordHashAlgorithm(): string
  {
    return $this->passwordHashAlgorithm;
  }

  public function lastInsertId(): ?int
  {
    return $this->lastInsertId;
  }

  public function __toString(): string
  {
    return $this->queryString;
  }

  public function queryString(): string
  {
    return $this->queryString;
  }

  public function type(): string
  {
    return $this->type;
  }

  public function setQueryString(string $queryString): void
  {
    $this->queryString = $queryString;
  }

  public function appendQueryString(string $tail): void
  {
    $this->queryString = trim($this->queryString) . " $tail";
  }

  public function alter(): SQLAlterDefinition
  {
    return new SQLAlterDefinition( query: $this );
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

  public function insertInto(string $tableName): SQLInsertIntoDefinition
  {
    $this->type = SQLQueryType::INSERT;
    return new SQLInsertIntoDefinition( query: $this, tableName: $tableName );
  }

  public function update(
    string $tableName,
    bool $lowPriority = false,
    bool $ignore = false,
  ): SQLUpdateDefinition
  {
    $this->type = SQLQueryType::UPDATE;
    return new SQLUpdateDefinition(
      query: $this,
      tableName: $tableName,
      lowPriority: $lowPriority,
      ignore: $ignore
    );
  }

  public function select(): SQLSelectDefinition
  {
    $this->type = SQLQueryType::SELECT;
    return new SQLSelectDefinition( query: $this );
  }

  public function deleteFrom(string $tableName, ?string $alias = null): SQLDeleteFromStatement
  {
    $this->type = SQLQueryType::DELETE;
    return new SQLDeleteFromStatement(
      query: $this,
      tableName: $tableName,
      alias: $alias
    );
  }

  public function truncateTable(string $tableName): SQLTruncateStatement
  {
    $this->type = SQLQueryType::TRUNCATE;
    return new SQLTruncateStatement( query: $this, tableName: $tableName );
  }

  public function execute(): SQLQueryResult
  {
    try
    {
      $statement = $this->db->prepare($this->queryString);

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
          SQLQueryType::SELECT => $statement->fetchAll(mode: $this->fetchMode),
          default => $statement->fetchAll()
        };

        if ($this->type() === SQLQueryType::INSERT)
        {
          $this->lastInsertId = $this->db->lastInsertId();
        }
  
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
      list($sqlCode, $driverCode, $message) = $statement->errorInfo();
      if (Config::environment('ENVIRONMENT') === 'PROD')
      {
        $message = 'Bad Request';
      }
      $errorResponse = match($sqlCode) {
        '23000' => new ConflictErrorResponse(message: $message),
        default => new BadRequestErrorResponse()
      };

      exit($errorResponse);
    }
  }

  public function debug(): void
  {
    exit($this);
  }
}

?>