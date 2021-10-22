<?php

namespace LifeRaft\Database;

use LifeRaft\Database\Attributes\Repository;
use LifeRaft\Database\Interfaces\IEntity;
use LifeRaft\Database\Interfaces\IRepository;

#[Repository]
class BaseRepository implements IRepository
{
  
  public function __construct(
    protected mixed $dbContext
  ) {
    # Get db connection

    # Handle Attribute meta data
  }

  public function find(mixed $predicate): array
  {
    return [];
  }

  public function get(int $id): IEntity|false
  {
    return new IEntity();
  }

  public function getAll(): array
  {
    return [];
  }

  public function add(IEntity $obj): void
  {
  }

  public function addRange(IEntity $entity): void
  {
  }

  public function remove(IEntity $obj): void
  {
  }

  public function removeRange(array $entities): void
  {
  }
}

?>