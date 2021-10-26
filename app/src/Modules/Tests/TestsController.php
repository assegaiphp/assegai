<?php

namespace LifeRaft\Modules\Tests;

use LifeRaft\Core\BaseController;
use LifeRaft\Core\Responses\Response;
use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\Attributes\Post;
use LifeRaft\Core\Request;
use stdClass;

class TestsController extends BaseController
{
  protected array $forbiddenMethods = [];

  public function __construct(
    protected Request $request,
    protected TestsRepository $testsRepository
  )
  {
    parent::__construct( request: $request );
  }

  #[Get]
  public function findAll(): Response
  {
    $data = $this->testsRepository->findAll(limit: $this->request->limit(), skip: $this->request->skip());
    return new Response( data: $data );
  }

  #[Get(path: '/:id')]
  public function find(int $id): Response
  {
    $data = $this->testsRepository->findOne(id: $id);
    return new Response( data: $data );
  }

  #[Post]
  public function createTest(stdClass $body): Response
  {
    $entity = TestEntity::newInstanceFromObject(object: $body);
    return new Response( data: $entity, dataOnly: true );
  }
}

?>