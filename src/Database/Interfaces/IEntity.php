<?php

namespace Assegai\Database\Interfaces;

use stdClass;

/**
 * The IEntity interface lists methods for mapping to a database table 
 * row (or collection when using MongoDB). You can create an entity by 
 * defining a new class and mark it with `#[Entity]`
 */
interface IEntity {
  public static function newInstanceFromObject(stdClass $object): IEntity;
  public static function newInstanceFromArray(array $array): IEntity;
  public static function columns(array $exclude = []): array;
  public static function isValidEntity(stdClass|IEntity $object): bool;
  public function values(array $exclude = []): array;
  public function columnValuePairs(array $exclude = []): array;
  public function toArray(array $exclude = []): array;
  public function toJSON(): string;
  public function schema(string $dialect = 'mysql'): string;
}

?>