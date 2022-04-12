<?php

namespace Assegai\Database;

use Assegai\Database\Types\DataSourceType;

class DataSourceOptions
{
  /**
    * @param DataSourceType $type,
    * @param null|string $host,
    * @param null|int $port,
    * @param null|string $username,
    * @param null|string $password,
    * @param null|string $database,
    * @param array<IEntity> $entities 
   */
  public function __construct(
    public readonly DataSourceType $type,
    protected readonly ?string $host = null,
    protected readonly ?int $port = null,
    public readonly ?string $username = null,
    public readonly ?string $password = null,
    public readonly ?string $database = null,
    public readonly array $entities = []
  ) { }

  public function getHost(): string
  {
    return $this->host ?? 'localhost';
  }

  public function getPort(): int
  {
    return $this->port ?? match($this->type) {
      DataSourceType::POSTGRESQL => 5432,
      DataSourceType::MARIADB,
      DataSourceType::MYSQL => 3306,
      DataSourceType::MSSQL => 1433,
      default => 3306
    };
  }
}
