<?php

namespace Assegai\Core;

use Assegai\Core\Attributes\Injectable;
use Assegai\Core\Attributes\Module;
use Assegai\Core\Interfaces\IModule;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class BaseModule implements IModule
{
  protected string $id;
  protected array $injectables = [];
  protected ReflectionClass $reflectionClass;

  public function __construct(
    protected ?array $providers = [],
    protected ?array $controllers = [],
    protected ?array $imports = [],
    protected ?array $exports = [],
  )
  {
    $this->id = uniqid(prefix: 'module-');

    $reflection = new ReflectionClass($this);
    $attributes = $reflection->getAttributes(Module::class);

    foreach ($attributes as $attribute)
    {
      $attributeInstance = $attribute->newInstance();

      $this->providers = $attributeInstance->providers;
      $this->controllers = $attributeInstance->controllers;
      $this->imports = $attributeInstance->imports;
      $this->exports = $attributeInstance->exports;
    }

    if (is_null($this->providers)) { $this->providers = []; }
    if (is_null($this->controllers)) { $this->controllers = []; }
    if (is_null($this->imports)) { $this->imports = []; }
    if (is_null($this->exports)) { $this->exports = []; }

    # For each imported module

    # get exported class
  }

  public function id(): string
  {
    return $this->id;
  }

  public function rootControllerName(): string|null
  {
    if (!empty($this->controllers) && isset($this->controllers[0]))
    {
      return $this->controllers[0];
    }

    return null;
  }

  public function resolveInjectables(): array
  {
    try
    {
      foreach ($this->providers as $provider)
      {
        $reflectionClass = new ReflectionClass($provider);
        $attributes = $reflectionClass->getAttributes(Injectable::class);

        if (!empty($attributes))
        {
          $constructor = $reflectionClass->getConstructor();
          $constructorParams = $constructor->getParameters();
          $instanceArgs = [];

          foreach ($constructorParams as $param)
          {
            if (empty($param->getType()))
            {
              continue;
            }
            $paramClass = $param->getType()->getName();
            $paramInstance = new $paramClass;
            $paramReflection = new ReflectionClass(objectOrClass: $paramClass);
            $paramAttributes = $paramReflection->getAttributes(Injectable::class);
            if (!empty($paramAttributes))
            {
              $instanceArgs[$param->getName()] = $paramInstance;
            }
          }

          $instance = $reflectionClass->newInstanceArgs(args: $instanceArgs);
          $this->injectables[$reflectionClass->getName()] = $instance;
        }
      }

    }
    catch (ReflectionException $exception)
    {
      exit($exception->getMessage());
    }

    return !is_null($this->injectables) ? $this->injectables : [];
  }

  public function injectables(): array
  {
    return !is_null($this->injectables) ? $this->injectables : [];
  }

  public function exports(): array
  {
    return $this->exports;
  }

  public function getDependencies(string $classname): array
  {
    global $app;
    $dependencies = [];

    try
    {
      $reflection = new ReflectionClass($classname);
      $constructor = $reflection->getConstructor();

      if ($constructor instanceof ReflectionMethod)
      {
        $params = $constructor->getParameters();

        foreach ($params as $param)
        {
          if ($param->getName() === 'request')
          {
            $dependencies['request'] = $app->request();
          }
          else if (isset($this->injectables()[$param->getType()->getName()]))
          {
            $dependencies[$param->getName()] = $this->injectables()[$param->getType()->getName()];
          }
        }
      }
    }
    catch (ReflectionException $exception)
    {
      exit($exception->getMessage());
    }

    return $dependencies;
  }

}

