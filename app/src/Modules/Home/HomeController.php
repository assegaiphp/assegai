<?php

namespace LifeRaft\Modules\Home;

use LifeRaft\Core\BaseController;
use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\Config;
use LifeRaft\Core\RequestMethod;
use LifeRaft\Core\Responses\Response;
use LifeRaft\Database\Queries\SQLQuery;

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
    extract(Config::get('databases')['mysql']['assegai_test']);
    $dsn = "mysql:dbname=$name;host=$host";

    try
    {
      $db = new \PDO( dsn: $dsn, username: $user, password: $password );
    }
    catch (\Exception $e)
    {
      die($e->getMessage());
    }

    $query = new SQLQuery( db: $db );
    // $query->select()->from();

    $data = [
      'name'        => 'Social Navigator API.',
      'description' => 'Social Navigator powered by Life Raft API',
      'version'     => '1.0.0',
      'copyright'   => '© ' . date('Y') . ' Life Raft',
      'db'          => $db
    ];

    return new Response( data: $data, data_only: true );
  }
}

?>