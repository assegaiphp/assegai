<?php

namespace Assegai\Lib\Authentication;

use Assegai\Core\Config;
use Assegai\Core\Responses\HttpStatusCode;
use Assegai\Core\Responses\Response;
use Assegai\Core\Responses\ResponseType;
use Assegai\Core\Result;
use Assegai\Database\Interfaces\IEntity;
use stdClass;

final class AuthResponse extends Response
{
  private string $accessToken = '';
  private array $authentication = [];
  private null|IEntity|stdClass $entity = null;
  private string $entityName = 'user';

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

    if (isset($this->data['authentication']))
    {
      $this->authentication = $this->data['authentication'];
    }
    
    $this->entityName =
      isset(Config::get('authentication')['jwt']['entityName'])
        ? Config::get('authentication')['jwt']['entityName']
        : 'user';

    if ($this->data instanceof Result)
    {
      $this->entity = $this->data[$this->entityName];
    }
  }

  public function accessToken(): string
  {
    return $this->accessToken;
  }

  public function toArray(): array
  {
    return [
      'accessToken'     => $this->accessToken,
      'authentication'  => $this->authentication,
      $this->entityName => json_decode($this->entity->toJSON())
    ];
  }

  public function toJSON(): string
  {
    return json_encode(value: $this->toArray());
  }
}

?>