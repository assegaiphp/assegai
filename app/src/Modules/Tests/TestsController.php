<?php

namespace LifeRaft\Modules\Tests;

use LifeRaft\Core\BaseController;
use LifeRaft\Core\Responses\Response;
use LifeRaft\Core\Attributes\Get;

class TestsController extends BaseController
{
  protected array $forbiddenMethods = [];

  #[Get]
  public function findAll(): Response
  {
    return new Response( data: ['This action returns all entities'], dataOnly: true );
  }

  #[Get(path: '/:id')]
  public function find(int $id): Response
  {
    return new Response( data: ['This action returns the entity with id: ' . $id], dataOnly: true );
  }
}

?>