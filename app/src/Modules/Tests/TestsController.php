<?php

namespace LifeRaft\Modules\Tests;

use LifeRaft\Core\BaseController;
use LifeRaft\Core\Responses\Response;
use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\Request;

class TestsController extends BaseController
{
  protected array $forbiddenMethods = [];

  public function __construct(
    protected Request $request,
    protected TestsRepository $testsRepository
  )
  {
    parent::__construct( request: $request );
    echo get_called_class() . "\n";
    var_export($testsRepository);
    exit;
  }

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