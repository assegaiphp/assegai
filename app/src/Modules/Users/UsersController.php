<?php

namespace LifeRaft\Modules\Users;

use LifeRaft\Core\Attributes\Controller;
use LifeRaft\Core\Attributes\Delete;
use LifeRaft\Core\BaseController;
use LifeRaft\Core\Responses\Response;
use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\Attributes\Patch;
use LifeRaft\Core\Attributes\Post;
use LifeRaft\Core\Attributes\Put;
use LifeRaft\Core\Request;
use LifeRaft\Database\Interfaces\IEntity;
use stdClass;

#[Controller(path: 'users', forbiddenMethods: [])]
class UsersController extends BaseController
{
  public function __construct(
    protected Request $request,
    protected UsersService $usersService,
    protected UsersRepository $usersRepository
  )
  {
    // debug_print_backtrace();
    // exit;    
  }

  #[Get]
  public function findAll(): Response
  {
    return new Response( data: ['This action returns all users'], dataOnly: true );
  }

  #[Get(path: '/:id')]
  public function find(int $id): Response
  {
    return new Response( data: ['This action returns the users entity with id: ' . $id], dataOnly: true );
  }

  #[Post]
  public function create(stdClass|array $body): Response
  {
    exit(var_export($this->repository, true));
    return new Response( data: ['This action creates a new users entity'], dataOnly: true );
  }

  #[Put(path: '/:id')]
  public function update(int $id, stdClass|IEntity $body): Response
  {
    return new Response( data: ['This action fully updates the users entity with id: ' . $id], dataOnly: true );
  }

  #[Patch(path: '/:id', action: Patch::UPDATE_ACTION)]
  public function partialUpdate(int $id, stdClass $body): Response
  {
    return new Response(data: ['This action partially updates the users entity with id: ' . $id], dataOnly: true);
  }

  #[Patch(path: '/:id', action: Patch::RESTORE_ACTION)]
  public function restore(int $id): Response
  {
    return new Response(data: ['This action restores the users entity with id: ' . $id], dataOnly: true);
  }

  #[Patch(path: '/:id', action: Patch::DELETE_ACTION)]
  public function softRemove(int $id): Response
  {
    return new Response(data: ['This action soft-removes the users entity with id: ' . $id], dataOnly: true);
  }

  #[Delete]
  public function removeAll(stdClass|array $body): Response
  {
    if ($body instanceof stdClass && isset($body->ids))
    {
      $idsString = implode(', ', $body->ids);
    }
    return new Response(data: "This action removes all users entities with ids $idsString", dataOnly: true);
  }

  #[Delete(path: '/:id')]
  public function remove(int $id): Response
  {
    return new Response(data: ['This action removes the users entity with id: ' . $id], dataOnly: true);
  }
}

?>