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
use LifeRaft\Core\Responses\BadRequestErrorResponse;
use LifeRaft\Database\Interfaces\IEntity;
use stdClass;

#[Controller(path: 'users', forbiddenMethods: [])]
class UsersController extends BaseController
{
  public function __construct(
    protected Request $request,
    protected UsersService $usersService,
    protected UsersRepository $usersRepository
  ) { }

  #[Get]
  public function findAll(mixed $body): Response
  {
    $params = match(gettype($body)) {
      'array' => $body,
      default => get_object_vars($body)
    };
    $conditions = '';
    foreach ($params as $key => $value)
    {
      $column = 
        isset(UserEntity::columns()[$key])
        ? UserEntity::columns()[$key]
        : $key;
      $conditions .= "`$column`=";
      $conditions .= is_numeric($value) ? $value : "'$value' AND ";
    }
    $conditions = trim($conditions, 'AND ');
    $result = $this->usersService->find(conditions: $conditions);

    if ($result->isError())
    {
      return new BadRequestErrorResponse(message: 'Invalid request.');
    }

    return new Response( data: $result->value() );
  }

  #[Get(path: '/:id')]
  public function find(int $id): Response
  {
    $result = $this->usersService->findOne(conditions: "`id`=$id");

    if ($result->isError())
    {
      return new BadRequestErrorResponse();
    }

    return new Response( data: $result->value(), dataOnly: true );
  }

  #[Post]
  public function create(stdClass|array $body): Response
  {
    $entity = match (gettype($body)) {
      'array' => UserEntity::newInstanceFromArray(array: $body),
      default => UserEntity::newInstanceFromObject(object: $body),
    };

    $result = $this->usersService->create(entity: $entity);

    if ($result->isError())
    {
      return new BadRequestErrorResponse();
    }

    return new Response( data: $result->value(), dataOnly: true );
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