<?php

namespace Assegai\Core\Injection;

use Assegai\Core\Exceptions\ClassNotFoundException;
use Assegai\Core\Interfaces\IContainer;

class InjectionContainer implements IContainer
{
  
  public function __construct(
    public readonly array $entries = []
  ) { }

  public function get(string $entryId): mixed
  {
    if ( $this->has(entryId: $entryId) )
    {
      return $this->entries[$entryId];
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
}