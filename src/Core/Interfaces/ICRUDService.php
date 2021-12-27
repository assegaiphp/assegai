<?php

namespace Assegai\Core\Interfaces;

use Assegai\Core\Result;
use Assegai\Database\Interfaces\IEntity;
use stdClass;

interface ICRUDService extends IService
{
  public static function instance(): IService|null;

  public function find(?string $conditions = null): Result;

  public function findOne(string $conditions): Result;

  public function create(IEntity $entity): Result;

  public function fullUpdate(IEntity|array $entity): Result;

  public function partialUpdate(stdClass|array $changes): Result;

  public function restore(int $id): Result;

  public function softRemove(int $id): Result;

  public function softRemoveRange(array $idsList): Result;

  public function remove(int $id): Result;

  public function removeRange(array $idsList): Result;
}

