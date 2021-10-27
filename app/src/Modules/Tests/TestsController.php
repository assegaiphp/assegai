<?php

namespace LifeRaft\Modules\Tests;

use LifeRaft\Core\Attributes\Controller;
use LifeRaft\Core\Attributes\Delete;
use LifeRaft\Core\BaseController;
use LifeRaft\Core\Responses\Response;
use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\Attributes\Post;
use LifeRaft\Core\Attributes\Put;
use LifeRaft\Core\Request;
use LifeRaft\Core\Responses\BadRequestErrorResponse;
use LifeRaft\Core\Responses\HttpStatus;
use LifeRaft\Core\Responses\NotFoundErrorResponse;
use LifeRaft\Database\Interfaces\IEntity;
use stdClass;

#[Controller(path: 'tests')]
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
  public function createTest(stdClass|array $body): Response
  {
    if (is_array(value: $body))
    {
      $entity = $this->testsRepository->addRange(entities: $body);
    }
    else
    {
      $entity = TestEntity::newInstanceFromObject(object: $body);
      $entity = $this->testsRepository->add( entity: $entity );
    }

    if ($entity === false)
    {
      return new BadRequestErrorResponse();
    }

    return new Response( data: $entity, dataOnly: true, status: $this->status );
  }

  #[Put(path: '/:id')]
  public function update(int $id, stdClass|IEntity $body): Response
  {
    $entity = TestEntity::newInstanceFromObject(object: $body);
    $entity->id = $id;
    $result = $this->testsRepository->update(entity: $entity);

    if ($result === false)
    {
      return new BadRequestErrorResponse();
    }

    return new Response(data: $result, dataOnly: true );
  }

  #[Delete(path: '/:id')]
  public function remove(int $id): Response
  {
    $entity = $this->testsRepository->findOne(id: $id);
    if (is_null($entity))
    {
      return new NotFoundErrorResponse();
    }
    $entityName = $this->testsRepository->entityName();
    $entity = $entityName::newInstanceFromObject($entity);
    $entity = $this->testsRepository->remove($entity);

    return new Response(data: $entity, status: HttpStatus::NoContent());
  }
}

?>