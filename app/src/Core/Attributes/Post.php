<?php

namespace LifeRaft\Core\Attributes;

use Attribute;
use LifeRaft\Core\Responses\HttpStatus;
use LifeRaft\Core\Responses\HttpStatusCode;
use stdClass;

/**
 * The HTTP POST method sends data to the server. The type of the body of 
 * the request is indicated by the Content-Type header.
 * 
 * The difference between PUT and POST is that PUT is idempotent: calling 
 * it once or several times successively has the same effect (that is no 
 * side effect), where successive identical POST may have additional 
 * effects, like passing an order several times.
 * 
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/POST
 */
#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_FUNCTION)]
class Post
{
  public array $tokens = [];
  public null|stdClass|array $body = null;
  public bool $canActivate = false;

  public function __construct(
    public string $path = '',
    public array $args = [],
    public ?HttpStatusCode $status = null
  ) {
    global $request;
    $this->tokens = explode('/', trim($path, '/'));
    $requestedURI = explode(separator: '/', string: (isset($_GET['path']) ? $_GET['path'] : '/') );
    if (is_null($this->status))
    {
      $this->status = HttpStatus::Created();
    }

    $body = $_POST;

    if (empty($body))
    {
      $body = $_FILES;
    }

    if (empty($body))
    {
      $body = file_get_contents('php://input');
    }

    $this->body = match( gettype($body) ) {
      'array' => json_decode( json_encode($body) ),
      'string' => json_decode( $body ),
      default => $body
    };

    if (is_null($this->body))
    {
      $this->body = new stdClass;
    }

    $this->args['body'] = $this->body;
    $this->canActivate = $request->method() === 'POST';
  }
}

?>