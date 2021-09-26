<?php

namespace LifeRaft\Core;

/**
 * A controller's purpose is to recieve specific requests for the 
 * application. The *routing* mechanism controls which controller 
 * recieves which requests.
 */
abstract class Controller
{
  protected array $handlers = [
    'get' => [],
    'post' => [],
    'put' => [],
    'patch' => [],
    'delete' => [],
    'options' => [],
  ];
  protected array $url = [];

  public function __construct()
  {
  }

  public abstract function handle_request(array $url): Response;
}

?>