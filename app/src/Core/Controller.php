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
    RequestMethod::GET => [],
    RequestMethod::POST => [],
    RequestMethod::PUT => [],
    RequestMethod::PATCH => [],
    RequestMethod::DELETE => [],
    RequestMethod::OPTIONS => [],
  ];
  protected array $url = [];

  public function __construct(
    protected Request $request
  )
  {
    var_export(strtolower($request->method()));
    exit;
  }

  public function handle_request(array $url): Response
  {
    # Check if handle exists

    # Else respond with Unknow error

    return new Response( data: [] );
  }
}

?>