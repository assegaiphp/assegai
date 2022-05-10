<?php

namespace Assegai\Core\Interfaces;

use Assegai\Core\Injection\InjectionToken;
use Assegai\Core\Injection\InjectorOptions;

/**
 * Concrete injectors implement this interface. Injectors are configured
 * with [providers](guide/glossary#provider) that associate
 * dependencies of various types with [injection tokens](guide/glossary#di-token).
 */
interface IInjector
{
  /**
   * Retrieves an instance from the injector based on the provided token.
   * 
   * @return mixed Returns the instance from the injector if defined, otherwise the `notFoundValue`.
   * @throws ClassNotFoundException When the `$notFoundValue` is `null` or `Injector.THROW_IF_NOT_FOUND`.
   */
  public static function get(string|InjectionToken|callable $token, mixed $notFoundValue = null): mixed;

  /**
   * Creates a new injector instance that provides one or more dependencies,
   * according to a given type or types of `StaticProvider`.
   */
  public static function create(InjectorOptions $options = new InjectorOptions(providers: [])): IInjector;
}