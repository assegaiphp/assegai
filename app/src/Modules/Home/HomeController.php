<?php

namespace LifeRaft\Modules\Home;

use LifeRaft\Core\Controller;
use LifeRaft\Core\Response;

class HomeController extends Controller
{
  public function handle_request(array $url): Response
  {
    $data = [
      'name' => 'Social Navigator API.',
      'description' => 'Social Navigator powered by Life Raft API',
      'version' => '1.0.0',
      'copyright' => '© ' . date('Y') . ' Life Raft',
    ];

    return new Response( data: $data, data_only: true );
  }
}

?>