<?php

namespace LifeRaft\Core\Attributes;

use Attribute;

/**
 * The **HTTP PATCH request method** applies partial modifications to a 
 * resource.
 * 
 * A PATCH request is considered a set of instructions on how to modify a 
 * resource. Contrast this with PUT; which is a complete representation of a resource.
 * 
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/PATCH
 */
#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_FUNCTION)]
class Patch
{
  const UPDATE_ACTION = 'UPDATE';
  const DELETE_ACTION = 'DELETE';

  public array $tokens = [];
  public mixed $body = null;
  public bool $canActivate = false;
  
  public function __construct(
    public string $path = '',
    public array $args = [],
    public string $action = Patch::UPDATE_ACTION
  ) {
    global $request;
    $this->tokens = explode('/', trim($path, '/'));
    $requestedURI = explode(separator: '/', string: (isset($_GET['path']) ? $_GET['path'] : '/') );

    foreach ($this->tokens as $index => $token)
    {
      if (str_starts_with($token, ':'))
      {
        # Get variable at path
        $value = null;

        if (isset($requestedURI[$index + 1]))
        {
          $value = $requestedURI[$index + 1];
        }
        $this->args[trim($token, ':')] = $value;
      }
    }

    $valid_actions = [Patch::UPDATE_ACTION, Patch::DELETE_ACTION];
    if (isset($_GET['action']))
    {
      $action = strtoupper($_GET['action']);

      if (in_array($action, $valid_actions))
      {
        $this->action = $action;
      }
    }

    $this->body = $request->body();
    $this->canActivate = $request->method() === 'PATCH';
  }
}

?>