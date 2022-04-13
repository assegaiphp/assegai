<?php

namespace Assegai\Database\Types;

enum SQLDialect: string
{
  case MYSQL = 'mysql';
  case POSTGRESSQL = 'pgsql';
  case MSSQL = 'mssql';
  case MARIADB = 'mariadb';
  case SQLITE = 'sqlite';
  case UNKNOWN = 'unknown';

  public static function fromString(string $case): SQLDialect
  {
    return match($case) {
      'mysql' => self::MYSQL,
      'pgsql' => self::POSTGRESSQL,
      'mssql' => self::MSSQL,
      'mariadb' => self::MARIADB,
      'sqlite' => self::SQLITE,
      'unknown' => self::UNKNOWN,
      default => self::UNKNOWN
    };
  }
}