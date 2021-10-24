<?php

namespace LifeRaft\Core\Interfaces;

interface IModule
{
  public function id(): string;

  public function injectables(): array;

  public function exports(): array;

  public function rootControllerName(): string|null;

  public function resolveInjectables(): array;

  public function getDependencies(string $classname): array;
}

?>