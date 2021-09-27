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
    RequestMethod::GET      => [],
    RequestMethod::POST     => [],
    RequestMethod::PUT      => [],
    RequestMethod::PATCH    => [],
    RequestMethod::DELETE   => [],
    RequestMethod::OPTIONS  => [],
  ];
  protected array $url = [];

  public function __construct(
    protected Request $request
  ) { }

  public function handle_request(array $url): Response
  {
    # Check if handle exists
    if (!isset($this->handlers[$this->request->method()]) || empty($this->handlers[$this->request->method()]))
    {
      return new NotFoundErrorResponse();
    }

    $handle_candidates = $this->handlers[$this->request->method()];

    foreach ($handle_candidates as $path => $handler)
    {
      if (!is_string($path))
      {
        return new BadRequestErrorResponse('Invalid path type: ' . gettype($path));
      }

      if (!is_array($handler))
      {
        return new BadRequestErrorResponse('Invalid handler. Expected array, got ' . gettype($handler));
      }
    }

    # Else respond with Unknow error

    return new Response( data: [] );
  }
}

?>