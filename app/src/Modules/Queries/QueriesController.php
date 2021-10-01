<?php

namespace LifeRaft\Modules\Queries;

use LifeRaft\Core\Controller;
use LifeRaft\Core\Response;
use LifeRaft\Core\Attributes\Get;

class QueriesController extends Controller
{
  #[Get(path: '/:id')]
  public function find(int $id): Response
  {
    return new Response(data: [
      'name' => "Finding query with id: $id"
    ]);
  }

  #[Get]
  public function find_all(): Response
  {
    return new Response(data: [
      'name' => 'List all queries'
    ]);
  }
}

?>