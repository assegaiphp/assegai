<?php

namespace Assegai\Database\Interfaces;

use Assegai\Database\Types\SQLDialect;
use stdClass;

/**
 * The IEntity interface lists methods for mapping to a database table 
 * row (or collection when using MongoDB). You can create an entity by 
 * defining a new class and mark it with `#[Entity]`
 */
interface IEntity {
  /**
   * Creates a new entity from the given plain object.
   * @param stdClass $object
   * 
   * @return IEntity Returns a new entity instance.
   */
  public static function newInstanceFromObject(stdClass $object): IEntity;

  /**
   * Creates a new entity from the given associative array.
   * @param stdClass $object
   * 
   * @return IEntity Returns a new entity instance.
   */
  public static function newInstanceFromArray(array $array): IEntity;

  /**
   * Returns a list of entity columns excluding the columns listed in the `$exclude` array.
   * 
   * @param array $exclude The list of column names to exclude.
   * 
   * @return array Returns a list of entity columns excluding the columns 
   * listed in the `$exclude` array.
   */
  public static function columns(array $exclude = []): array;

  /**
   * Specifies whether the given object is a valid entity.
   * 
   * @param stdClass|IEntity $object The object in querstion.
   * 
   * @return bool Returns `true` if the object is a valid entity, `false` 
   * otherwise.
   */
  public static function isValidEntity(stdClass|IEntity $object): bool;

  /**
   * Returns a list of entity values excluding the values listed in the 
   * `$exclude` array.
   * 
   * @param array $exclude The list of column names to exclude.
   * 
   * @return array Returns a list of entity values excluding the values 
   * listed in the `$exclude` array.
   */
  public function values(array $exclude = []): array;

  /**
   * Returns a list of entity column-value pairs excluding the column-value 
   * pairs listed in the `$exclude` array.
   * 
   * @param array $exclude The list of column names to exclude.
   * 
   * @return array Returns a list of entity column-value pairs excluding the 
   * column-value pairs listed in the `$exclude` array.
   */
  public function columnValuePairs(array $exclude = []): array;

  /**
   * Converts the entity into an associative array, excluding the 
   * properties listed in the `$exclude` array
   * 
   * @param array $exclude The list of column properties to exclude.
   * 
   * @return array Returns an associative array representation of the entity excluding 
   * the properties listed in the `$exclude` array
   */
  public function toArray(array $exclude = []): array;

  /**
   * Converts the enitity into a JSON string.
   * 
   * @return string Returns a JSON string representation of the entity.
   */
  public function toJSON(): string;

  /**
   * Converts the entity into a PHP plain object (i.e. instance of `stdClass`).
   * 
   * @return stdClass Returns a plain object representation of the entity.
   */
  public function toPlainObject(): stdClass;

  /**
   * Generates an SQL table schema for the entity of the given dialect.
   * 
   * @param string|SQLDialect $dialect The SQL dialect for which the schema should be generated.
   * 
   * @return string Returns an SQL table schema.
   */
  public function schema(string|SQLDialect $dialect = 'mysql'): string;

  /**
   * Returns the name of the database table associated with the entity.
   * 
   * @return string Returns the name of the database table associated with the entity.
   */
  public function getTableName(): string;
}

?>