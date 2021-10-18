<?php

namespace LifeRaft\Database\Queries;

class SQLDataTypes
{
  /* Numeric Data Types */
  const TINYINT = 'TINYINT';
  const TINYINT_UNSIGNED = 'TINYINT UNSIGNED';
  const BOOLEAN = 'BOOLEAN';
  const SMALLINT = 'SMALLINT';
  const SMALLINT_UNSIGNED = 'SMALLINT UNSIGNED';
  const MEDIUMINT = 'MEDIUMINT';
  const MEDIUMINT_UNSIGNED = 'MEDIUMINT UNSIGNED';
  const INT = 'INT';
  const INT_UNSIGNED = 'INT UNSIGNED';
  const BIGINT = 'BIGINT';
  const BIGINT_UNSIGNED = 'BIGINT UNSIGNED';
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

  public static function isNumeric(string $type): bool
  {
    return in_array(
      $type,
      [
        SQLDataTypes::TINYINT,
        SQLDataTypes::TINYINT_UNSIGNED,
        SQLDataTypes::BOOLEAN,
        SQLDataTypes::SMALLINT,
        SQLDataTypes::SMALLINT_UNSIGNED,
        SQLDataTypes::MEDIUMINT,
        SQLDataTypes::MEDIUMINT_UNSIGNED,
        SQLDataTypes::INT,
        SQLDataTypes::INT_UNSIGNED,
        SQLDataTypes::BIGINT,
        SQLDataTypes::BIGINT_UNSIGNED,
        SQLDataTypes::DECIMAL,
        SQLDataTypes::FLOAT,
        SQLDataTypes::DOUBLE,
        SQLDataTypes::BIT,
      ]
    );
  }

  public static function isString(string $type): bool
  {
    return in_array(
      $type,
      [
        SQLDataTypes::BINARY,
        SQLDataTypes::BLOB,
        SQLDataTypes::TEXT,
        SQLDataTypes::CHAR,
        SQLDataTypes::ENUM,
        SQLDataTypes::INET6,
        SQLDataTypes::JSON,
        SQLDataTypes::MEDIUMBLOB,
        SQLDataTypes::MEDIUMTEXT,
        SQLDataTypes::LONGBLOB,
        SQLDataTypes::LONGTEXT,
        SQLDataTypes::ROW,
        SQLDataTypes::TINYBLOB,
        SQLDataTypes::TINYTEXT,
        SQLDataTypes::VARBINARY,
        SQLDataTypes::VARCHAR,
        SQLDataTypes::SET,
        SQLDataTypes::UUID,
      ]
    );
  }

  public static function isDateTime(string $type): bool
  {
    return in_array(
      $type,
      [
        SQLDataTypes::DATE,
        SQLDataTypes::TIME,
        SQLDataTypes::DATETIME,
        SQLDataTypes::TIMESTAMP,
        SQLDataTypes::YEAR,
        SQLDataTypes::AUTO_INCREMENT,
        SQLDataTypes::NULL,
      ]
    );
  }

  public static function isGeoSpatial(string $type): bool
  {
    return in_array(
      $type,
      [
        SQLDataTypes::POINT,
        SQLDataTypes::LINESTRING,
        SQLDataTypes::POLYGON,
        SQLDataTypes::MULTIPOINT,
        SQLDataTypes::MULTILINESTRING,
        SQLDataTypes::MULTIPOLYGON,
        SQLDataTypes::GEOMETRYCOLLECTION,
        SQLDataTypes::GEOMETRY,
      ]
    );
  }
}

?>