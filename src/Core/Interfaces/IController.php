<?php

namespace Assegai\Core\Interfaces;

use Assegai\Core\Responses\Response;

interface IController
{
  /**
   * Handles controller initialization allowing the user to determine the initialization step.
   */
  public function init(): void;

  /**
   * @param string[] $url
   * 
   * @return Response Returns a `Response` object.
   */
  public function handleRequest(array $url): Response;

  /**
   * @param Response $response The response to return to the client.
   */
  public function respond(Response $response): never;
}

