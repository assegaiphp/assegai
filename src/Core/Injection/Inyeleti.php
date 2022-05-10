<?php

namespace Assegai\Core\Injection;

use Assegai\Core\Exceptions\ClassNotFoundException;
use Assegai\Core\Exceptions\Container\ContainerException;
use Assegai\Core\Exceptions\Container\ResloveException;
use Assegai\Core\Interfaces\IContainer;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

class Inyeleti implements IContainer
{
  
  public function __construct(
    public readonly array $entries = []
  ) { }

  public function get(string $entryId): mixed
  {
    if ( $this->has(entryId: $entryId) )
    {
      $entry = $this->entries[$entryId];

      return $entry($this);
    }

    
  }

  public function has(string $entryId): bool
  {
    if ( ! key_exists($entryId, $this->entries) )
    {
      throw new ClassNotFoundException(className: $entryId);
    }

    return isset($this->entries[$entryId]);
  }

  public function resolve(string $id): mixed
  {
    # 1. Inspect the class that we are trying to get from the container
    $reflectionClass = new ReflectionClass($id);

    if (! $reflectionClass->isInstantiable() )
    {
      throw new ContainerException("Class '{$id}' is not instantiable");
    }

    # 2. Inspect the constructor of the class
    $constructor = $reflectionClass->getConstructor();

    if (! $constructor )
    {
      return new $id;
    }

    # 3. Inspect the constructor parameters (dependencies)
    $parameters = $constructor->getParameters();

    if (! $parameters )
    {
      return new $id;
    }

    # 4. If the constructor parameter is a class try to resolve it using the container
    $dependencies = $this->resolveDependencies(id: $id, parameters: $parameters);

    return $reflectionClass->newInstanceArgs($dependencies);
  }

  /**
   * @param string $id The entry id.
   * @param ReflectionParameter[] $parameters
   */
  private function resolveDependencies(string $id, array $parameters): array
  {
    return array_map(function(ReflectionParameter $param) use ($id) {
      $paramName = $param->getName();
      $paramType = $param->getType();
      $resolveErrorPrefix = "Failed to resolve type $paramName";

      if (! $paramType )
      {
        throw new ResloveException(id: $id, message: "$resolveErrorPrefix - Illegal type: Undefined");
      }

      if ($paramType instanceof ReflectionUnionType)
      {
        throw new ResloveException(id: $id, message: "$resolveErrorPrefix - Illegal type: Union");
      }

      if ($paramType instanceof ReflectionNamedType && ! $paramType->isBuiltin())
      {
        return $this->get($paramType->getName());
      }
    }, $parameters);
  }
}