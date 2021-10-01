<?php

namespace LifeRaft\Modules\Home;

use LifeRaft\Core\Controller;
use LifeRaft\Core\Response;
use LifeRaft\Core\Attributes\Get;

class HomeController extends Controller
{
  #[Get]
  public function default(): Response
  {
    $data = [
      'name' => 'Social Navigator API.',
      'description' => 'Social Navigator powered by Life Raft API',
      'version' => '1.0.0',
      'copyright' => '© ' . date('Y') . ' Life Raft',
    ];

    return new Response( data: $data, data_only: true );
  }

  #[Get(path: '/:id/homies')]
  public function find(int $id): Response
  {
    return new Response( data: ['id' => $id, 'name' => 'Homie #' . $id], data_only: true );
  }

  #[Get(path: '/:id')]
  public function list_homies(int $id): Response
  {
    return new Response(data: [
      ['id' => 1, 'name' => 'Homie #1'],
      ['id' => 2, 'name' => 'Homie #2'],
      ['id' => 3, 'name' => 'Homie #3'],
      ['id' => 4, 'name' => 'Homie #4'],
      ['id' => 5, 'name' => 'Homie #5'],
    ]);
  }
}

?>