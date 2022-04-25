<?php

namespace Assegai\Core\Injection;

class InjectorOptions
{
  /**
   * Constructs the `InjectorOptions`
   * @param array<StaticProvider> $providers An array of providers.
   * @param null|Injector $parent A parent injector.
   * @param null|string $name A developer-defined identifying name for the new injector.
   */
  public function __construct(
    public readonly array $providers,
    public readonly ?Injector $parent = null,
    public readonly ?string $name = null
  ) { }
}