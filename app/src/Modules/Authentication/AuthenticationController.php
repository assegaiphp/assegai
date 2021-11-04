<?php

namespace LifeRaft\Modules\Authentication;

use LifeRaft\Core\Attributes\Controller;
use LifeRaft\Core\Attributes\Delete;
use LifeRaft\Core\BaseController;
use LifeRaft\Core\Responses\Response;
use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\Attributes\Patch;
use LifeRaft\Core\Attributes\Post;
use LifeRaft\Core\Attributes\Put;
use LifeRaft\Core\Request;
use LifeRaft\Core\RequestMethod;
use LifeRaft\Database\Interfaces\IEntity;
use stdClass;

#[Controller(
  path: 'authentication',
  forbiddenMethods: [
    RequestMethod::GET,
    RequestMethod::PUT,
    RequestMethod::PATCH,
    RequestMethod::DELETE,
  ]
)]
class AuthenticationController extends BaseController
{
  public function __construct(
    protected Request $request,
    protected AuthenticationService $authenticationService
  )
  {
    parent::__construct(request: $request);
  }

  #[Get]
  public function findAll(): Response
  {
    return new Response( data: ['This action returns all authentication'], dataOnly: true );
  }

  #[Get(path: '/:id')]
  public function find(int $id): Response
  {
    return new Response( data: ['This action returns the authentication entity with id: ' . $id], dataOnly: true );
  }

  #[Post]
  public function create(stdClass|array $body): Response
  {
    $result = $this->authenticationService->create(entity: $body);
    return new Response( data: $result, dataOnly: true );
  }

  #[Put(path: '/:id')]
  public function update(int $id, stdClass|IEntity $body): Response
  {
    return new Response( data: ['This action fully updates the authentication entity with id: ' . $id], dataOnly: true );
  }

  #[Patch(path: '/:id', action: Patch::UPDATE_ACTION)]
  public function partialUpdate(int $id, stdClass $body): Response
  {
    return new Response(data: ['This action partially updates the authentication entity with id: ' . $id], dataOnly: true);
  }

  #[Patch(path: '/:id', action: Patch::RESTORE_ACTION)]
  public function restore(int $id): Response
  {
    return new Response(data: ['This action restores the authentication entity with id: ' . $id], dataOnly: true);
  }

  #[Patch(path: '/:id', action: Patch::DELETE_ACTION)]
  public function softRemove(int $id): Response
  {
    return new Response(data: ['This action soft-removes the authentication entity with id: ' . $id], dataOnly: true);
  }

  #[Delete]
  public function removeAll(array $ids): Response
  {
    $idsString = implode(', ', $ids);
    return new Response(data: "This action removes all authentication entities with ids $idsString", dataOnly: true);
  }

  #[Delete(path: '/:id')]
  public function remove(int $id): Response
  {
    return new Response(data: ['This action removes the authentication entity with id: ' . $id], dataOnly: true);
  }
}

?>