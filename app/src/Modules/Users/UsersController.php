<?php

namespace LifeRaft\Modules\Users;

use LifeRaft\Core\Controller;
use LifeRaft\Core\Response;

class UsersController extends Controller
{
  public function handle_request(array $url): Response
  {
    return new Response( data: [] );
  }
}

?>