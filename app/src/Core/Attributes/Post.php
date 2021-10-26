<?php

namespace LifeRaft\Core\Attributes;

use Attribute;
use LifeRaft\Core\Responses\HttpStatus;
use LifeRaft\Core\Responses\HttpStatusCode;
use stdClass;

#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_FUNCTION)]
class Post
{
  public array $tokens = [];
  public stdClass $body;

  public function __construct(
    public string $path = '',
    public array $args = [],
    public ?HttpStatusCode $status = null
  ) {
    $this->tokens = explode('/', trim($path, '/'));
    $requestedURI = explode(separator: '/', string: (isset($_GET['path']) ? $_GET['path'] : '/') );
    if (is_null($this->status))
    {
      $this->status = HttpStatus::Created();
    }
    http_response_code($this->status->code());

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

    $this->args['body'] = $this->body;
  }
}

?>