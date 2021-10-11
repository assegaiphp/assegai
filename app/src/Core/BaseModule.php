<?php

namespace LifeRaft\Core;

use LifeRaft\Core\Attributes\Module;

class BaseModule
{
  protected string $id;

  public function __construct(
    protected ?array $providers = [],
    protected ?array $controllers = [],
    protected ?array $imports = [],
    protected ?array $exports = [],
  )
  {
    $this->id = uniqid('module-');

    $reflection = new \ReflectionClass($this);
    $attributes = $reflection->getAttributes(Module::class);

    foreach ($attributes as $attribute)
    {
      $attribute_instance = $attribute->newInstance();

      $this->providers = $attribute_instance->providers;
      $this->controllers = $attribute_instance->controllers;
      $this->imports = $attribute_instance->imports;
      $this->exports = $attribute_instance->exports;
    }

    $this->resolveDependencies();
  }

  public function resolveDependencies(): void
  {
    // if (!empty($this->controllers()))
    // {
    //   $controller = $this->controllers()[0];
    //   $controller_reflection = new \ReflectionClass($controller);
    //   $params = $controller_reflection->getConstructor()->getParameters();
      
    //   foreach ($params as $param)
    //   {
    //     echo $param->getName();
    //   }
    // }

    # Load imports
    foreach ($this->imports as $import)
    {
      echo $import . "\n";
    }

    # Load service providers
    foreach ($this->providers as $provider)
    {
      echo $provider . "\n";
      // new $provider();
    }

    exit;
  }

  public function id(): string
  {
    return $this->id;
  }

  public function injectables(): array
  {
    return !is_null($this->providers) ? $this->providers : [];
  }

  public function controllers(): array
  {
    return !is_null($this->controllers) ? $this->controllers : [];
  }

  public function exports(): array
  {
    return !is_null($this->exports) ? $this->exports : [];
  }
}

?>