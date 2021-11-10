<?php

namespace Assegai\Modules\Home;

use Assegai\Core\Attributes\Controller;
use Assegai\Core\BaseController;
use Assegai\Core\Attributes\Get;
use Assegai\Core\RequestMethod;
use Assegai\Core\Responses\Response;

#[Controller(forbiddenMethods: [
  RequestMethod::DELETE,
  RequestMethod::HEAD,
  RequestMethod::PATCH,
  RequestMethod::POST,
  RequestMethod::PUT,
])]
class HomeController extends BaseController
{
  public function __construct(
    private HomeService $homeService
  ) { }

  #[Get]
  public function default(): Response
  {
    $data = [
      'name'        => 'Social Navigator API.',
      'description' => 'Social Navigator powered by Life Raft API',
      'version'     => '1.0.0',
      'copyright'   => '© ' . date('Y') . ' Life Raft',
    ];

    return new Response( data: $data, dataOnly: true );
  }
}

?>