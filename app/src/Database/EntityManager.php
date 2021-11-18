<?php

namespace Assegai\Database;

final class EntityManager
{
  
  public function __construct(
    private array $repositories = []
  ) { }

  public function save(): void
  {
    foreach ($this->repositories as $repository)
    {
      $repository->commit();
    }
  }
}

