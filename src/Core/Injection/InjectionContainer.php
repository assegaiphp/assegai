<?php

namespace Assegai\Core\Injection;

use Assegai\Core\Exceptions\ClassNotFoundException;
use Assegai\Core\Interfaces\IContainer;

class InjectionContainer implements IContainer
{
  
  public function __construct(
    public readonly array $entries = []
  ) { }

  public function get(string $entryIdentifier): mixed
  {
    if ( $this->has(entryIdentifier: $entryIdentifier) )
    {
      return $this->entries[$entryIdentifier];
    }

    
  }

  public function has(string $entryIdentifier): bool
  {
    if ( ! key_exists($entryIdentifier, $this->entries) )
    {
      throw new ClassNotFoundException(className: $entryIdentifier);
    }

    return isset($this->entries[$entryIdentifier]);
  }
}