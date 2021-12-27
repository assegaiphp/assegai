<?php

namespace Assegai\Core\Interfaces;

use Assegai\Core\Responses\Response;

interface IController
{
  public function handleRequest(array $url): Response;

  public function respond(Response $response): void;
}

