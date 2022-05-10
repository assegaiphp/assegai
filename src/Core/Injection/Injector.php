<?php

namespace Assegai\Core\Injection;

use Assegai\Core\Interfaces\IInjector;

class Injector implements IInjector
{
  # TODO: #12 Implement Injector @amasiye
  

  /**
   * Constructs an injector.
   * 
   * @param array<StaticProvider> $providers An array of providers.
   * @param null|Injector $parent A parent injector.
   * @param null|string $name A developer-defined identifying name for the new injector.
   */
  private function __construct(
    public readonly array $providers = [],
    public readonly ?Injector $parent = null,
    public readonly ?string $name = null
  ) { }

  /**
   * Retrieves an instance from the injector based on the provided token.
   * 
   * @return mixed Returns the instance from the injector if defined, otherwise the `notFoundValue`.
   * @throws ClassNotFoundException When the `$notFoundValue` is `null` or `Injector.THROW_IF_NOT_FOUND`.
   */
  public static function get(
    string|InjectionToken|callable $token,
    mixed $notFoundValue = null
  ): mixed
  {

  }

  /**
   * Creates a new injector instance that provides one or more dependencies,
   * according to a given type or types of `StaticProvider`.
   * 
   * @param InjectorOptions $options A set of properties for creating an Injector instance
   */
  public static function create(InjectorOptions $options = new InjectorOptions(providers: [])): IInjector
  {
    return new Injector(providers: $options->providers, parent: $options->parent, name: $options->name);
  }
}

