<?php

namespace LifeRaft\Database\Queries;

final class SQLCreateDefinition
{
  public function __construct(
    protected SQLQuery $query
  ) {}

  public function table(
    string $tableName,
    bool $isTemporary = false,
    bool $checkIfNotExists = true
  ): SQLCreateTableStatement
  {
    return new SQLCreateTableStatement(
      query: $this->query,
      tableName: $tableName,
      isTemporary: $isTemporary,
      checkIfNotExists: $checkIfNotExists
    );
  }
  
  public function database(
    string $dbName,
    string $defaultCharacterSet = 'utf8mb4',
    string $defaultCollation = 'utf8mb4_general_ci',
    bool $defaultEncryption = true,
  ): SQLCreateDatabaseStatement
  {
    return new SQLCreateDatabaseStatement(
      query: $this->query,
      dbName: $dbName,
      defaultCharacterSet: $defaultCharacterSet,
      defaultCollation: $defaultCollation,
      defaultEncryption: $defaultEncryption
    );
  }

  public function execute(): SQLQueryResult
  {
    return $this->query->execute();
  }
}

?>