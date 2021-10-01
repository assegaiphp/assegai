<?php

namespace LifeRaft\Modules\Home;

use LifeRaft\Core\Controller;
use LifeRaft\Core\Response;
use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\RequestMethod;

class HomeController extends Controller
{
  protected array $forbidden_methods = [
    RequestMethod::DELETE,
    RequestMethod::HEAD,
    RequestMethod::OPTIONS,
    RequestMethod::PATCH,
    RequestMethod::POST,
    RequestMethod::PUT,
  ];

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
}

?>