<?php

namespace LifeRaft\Core;

use LifeRaft\Core\Attributes\Injectable;

#[Injectable]
class Service
{
  protected static ?Service $instance;

  protected string $id;

  public function __construct()
  {
    $this->id = uniqid();

    if (is_null(Service::$instance))
    {
      Service::$instance = $this;
    }
  }

  public static function instance(): Service|null
  {
    return Service::$instance;
  }

  public function findAll(): Result
  {
    return new Result();
  }

  public function find(int $id): Result
  {
    return new Result();
  }

  public function create(mixed $entity): Result
  {
    return new Result();
  }

  public function update(): Result
  {
    return new Result();
  }

  public function patch(): Result
  {
    return new Result();
  }

  public function delete(): Result
  {
    return new Result();
  }
}

?>