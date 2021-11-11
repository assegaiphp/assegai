<?php

namespace Assegai\Lib\Authentication\Strategies;

use Assegai\Core\App;
use Assegai\Core\Interfaces\IService;
use Assegai\Lib\Authentication\Interfaces\IAuthStrategy;

class BaseAuthenticationStrategy implements IAuthStrategy
{
  public function __construct(
    protected ?string $name = '',
    protected ?App $app = null,
    protected ?IService $authenticationService = null,
    protected ?IService $entityService = null,
  ) { }

  public function name(): string
  {
    return $this->name;
  }
  
  public function setName(string $name): void
  {
    $this->name = $name;
  }

  public function app(): ?App
  {
    return $this->app;
  }

  public function setApp(App $app): void
  {
    $this->app = $app;
  }

  public function authenticationService(): ?IService
  {
    return $this->authenticationService;
  }

  public function setAuthenticationService(IService $authenticationService): void
  {
    $this->authenticationService = $authenticationService;
  }

  public function entityService(): ?IService
  {
    return $this->entityService;
  }

  public function setEntityService(IService $entityService): void
  {
    $this->entityService = $entityService;
  }

  public function authenticate(mixed $data, mixed $params): mixed
  {
    return null;
  }
}
