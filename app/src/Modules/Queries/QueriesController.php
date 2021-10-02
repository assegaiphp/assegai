<?php

namespace LifeRaft\Modules\Queries;

use LifeRaft\Core\Controller;
use LifeRaft\Core\Response;
use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\Attributes\Post;

class QueriesController extends Controller
{
  #[Get(path: '/:id')]
  public function find(int $id): Response
  {
    return new Response(data: [
      'name' => "Finding query with id: $id"
    ]);
  }

  #[Get(path: '/*')]
  public function find_all(): Response
  {
    return new Response(data: [
      'name' => 'This action returns all queries'
    ]);
  }

  #[Post]
  public function create(): Response
  {
    return new Response(data: ['name' => 'This action adds a new query']);
  }
}

?>