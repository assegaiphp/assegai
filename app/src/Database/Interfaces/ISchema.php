<?php

namespace LifeRaft\Database\Interfaces;

interface ISchema
{
  public static function create(string $entityClass): ?bool;
  public static function createIfNotExists(string $entityClass): ?bool;
  public static function rename(string $from, string $to): ?bool;
  public static function alter(string $entityClass): ?bool;
  public static function info(string $entityClass): ?string;
  public static function truncate(string $entityClass): ?bool;
  public static function drop(string $name): ?bool;
  public static function dropIfExists(string $name): ?bool;
}

?>