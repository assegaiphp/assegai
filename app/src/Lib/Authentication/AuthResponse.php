<?php

namespace LifeRaft\Lib\Authentication;

use LifeRaft\Core\Responses\HttpStatusCode;
use LifeRaft\Core\Responses\Response;
use LifeRaft\Core\Responses\ResponseType;

final class AuthResponse extends Response
{
  private string $accessToken = '';
  private array $authentication = [];

  public function __construct(
    private mixed $data = [],
    private ?ResponseType $type = null,
    private ?HttpStatusCode $status = null
  )
  {
    parent::__construct(data: $data, type: $type, status: $status, dataOnly: true);
  }

  public function toArray(): array
  {
    return [
      'accessToken' => $this->accessToken,
      'authentication' => $this->authentication,
    ];
  }
}

?>