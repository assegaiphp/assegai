<?php

namespace LifeRaft\Modules\Users;

use LifeRaft\Core\Controller;
use LifeRaft\Core\Response;
use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\Attributes\Post;
use stdClass;

class UsersController extends Controller
{
  #[Get]
  public function findAll(): Response
  {
    return new Response( data: ['This action finds all users'], data_only: true);
  }

  #[Get( path: '/:id')]
  public function find(int $id): Response
  {
    return new Response( data: [ 'This action finds a user with id ' . $id ], data_only: true );
  }

  #[Post]
  public function create(stdClass $body): Response
  {
    var_export($body); exit;
    return new Response( data: [ 'This action creates a new user' ], data_only: true );
  }
}

?>