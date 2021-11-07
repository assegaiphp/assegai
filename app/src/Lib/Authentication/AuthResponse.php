<?php

namespace LifeRaft\Lib\Authentication;

use LifeRaft\Core\Responses\HttpStatusCode;
use LifeRaft\Core\Responses\Response;
use LifeRaft\Core\Responses\ResponseType;
use LifeRaft\Core\Result;

final class AuthResponse extends Response
{
  private string $accessToken = '';
  private array $authentication = [];

  public function __construct(
    protected mixed $data = [],
    protected ?ResponseType $type = null,
    protected ?HttpStatusCode $status = null
  )
  {
    parent::__construct(data: $data, type: $type, status: $status, dataOnly: true);
    $this->accessToken = match(gettype($this->data)) {
      'array' => isset($this->data['accessToken']) ? $this->data['accessToken'] : '',
      'object' => match (get_class($this->data)) {
        Result::class => isset($this->data['accessToken']) ? $this->data['accessToken'] : '',
        default => strval($this->data)
      },
      default => $this->data
    };
  }

  public function accessToken(): string
  {
    return $this->accessToken;
  }

  public function toArray(): array
  {
    return [
      'accessToken' => $this->accessToken,
      'authentication' => $this->authentication,
    ];
  }

  public function toJSON(): string
  {
    return json_encode(value: $this->toArray());
  }
}

?>