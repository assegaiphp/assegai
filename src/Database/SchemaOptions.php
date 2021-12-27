<?php

namespace Assegai\Database;

class SchemaOptions
{
  public function __construct(
    protected string $dbName = 'navigator',
    protected string $dialect = 'mysql',
  ) { }

  public function dbName(): string { return $this->dbName; }

  public function dialect(): string { return $this->dialect; }
}

