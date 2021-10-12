<?php

namespace LifeRaft\Modules\Home;

use LifeRaft\Core\BaseController;
use LifeRaft\Core\Response;
use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\RequestMethod;

class HomeController extends BaseController
{
  public function __construct(
    private HomeService $homeService
  ) { }

  protected array $forbidden_methods = [
    RequestMethod::DELETE,
    RequestMethod::HEAD,
    RequestMethod::PATCH,
    RequestMethod::POST,
    RequestMethod::PUT,
  ];

  #[Get]
  public function default(): Response
  {
    $data = [
      'name'        => 'Social Navigator API.',
      'description' => 'Social Navigator powered by Life Raft API',
      'version'     => '1.0.0',
      'copyright'   => '© ' . date('Y') . ' Life Raft',
    ];

    return new Response( data: $data, data_only: true );
  }
}

?>