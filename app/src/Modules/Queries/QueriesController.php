<?php

namespace LifeRaft\Modules\Queries;

use LifeRaft\Core\Controller;
use LifeRaft\Core\Response;

class QueriesController extends Controller
{
  public function handle_request(array $url): Response
  {
    return new Response( data: [] );
  }
}

?>