<?php

namespace LifeRaft\Core\Interfaces;

use LifeRaft\Core\Responses;
use LifeRaft\Core\Responses\Response;

interface IController
{
  public function handleRequest(array $url): Response;

  public function respond(Response $response): void;
}

?>