<?php

namespace LifeRaft\Database;

class SQLDataTypes
{
  /* Numeric Data Types */
  const TINYINT = 'TINYINT';
  const BOOLEAN = 'BOOLEAN';
  const SMALLINT = 'SMALLINT';
  const MEDIUMINT = 'MEDIUMINT';
  const INT = 'INT';
  const BIGINT = 'BIGINT';
  const DECIMAL = 'DECIMAL';
  const FLOAT = 'FLOAT';
  const DOUBLE = 'DOUBLE';
  const BIT = 'BIT';

  /* String Data Types */
  const BINARY = 'BINARY';
  const BLOB = 'BLOB';
  const TEXT = 'TEXT';
  const CHAR = 'CHAR';
  const ENUM = 'ENUM';
  const INET6 = 'INET6';
  const JSON = 'JSON';
  const MEDIUMBLOB = 'MEDIUMBLOB';
  const MEDIUMTEXT = 'MEDIUMTEXT';
  const LONGBLOB = 'LONGBLOB';
  const LONGTEXT = 'LONGTEXT';
  const ROW = 'ROW';
  const TINYBLOB = 'TINYBLOB';
  const TINYTEXT = 'TINYTEXT';
  const VARBINARY = 'VARBINARY';
  const VARCHAR = 'VARCHAR';
  const SET = 'SET';
  const UUID = 'UUID';

  /* Date and Time Data Types */
  const DATE = 'DATE';
  const TIME = 'TIME';
  const DATETIME = 'DATETIME';
  const TIMESTAMP = 'TIMESTAMP';
  const YEAR = 'YEAR';
  const AUTO_INCREMENT = 'AUTO_INCREMENT';
  const NULL = 'NULL';

  /* Geometry and Spatial Data Types */
  const POINT = 'POINT';
  const LINESTRING = 'LINESTRING';
  const POLYGON = 'POLYGON';
  const MULTIPOINT = 'MULTIPOINT';
  const MULTILINESTRING = 'MULTILINESTRING';
  const MULTIPOLYGON = 'MULTIPOLYGON';
  const GEOMETRYCOLLECTION = 'GEOMETRYCOLLECTION';
  const GEOMETRY = 'GEOMETRY';
}

?>