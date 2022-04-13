<?php

namespace Assegai\Database;

use Assegai\Database\Types\SQLDialect;

class SchemaOptions
{
  public function __construct(
    protected string $dbName = 'navigator',
    protected string|SQLDialect $dialect = SQLDialect::MYSQL,
    public readonly ?string $entityPrefix = null,
    public readonly bool $logging = false,
    public readonly bool $dropSchema = false,
    public readonly bool $synchronize = false,
  ) { }

  public function dbName(): string { return $this->dbName; }

  // TODO: #90 ** BREAKING CHANGE ** Change return type to SQLDialect @amasiye
  public function dialect(): string { return is_string($this->dialect) ? $this->dialect : $this->dialect->value; }
}

