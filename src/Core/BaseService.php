<?php

namespace Assegai\Core;

use Assegai\Core\Attributes\Injectable;
use Assegai\Core\Interfaces\IService;

#[Injectable]
class BaseService implements IService
{
  protected static ?IService $instance;

  protected string $id;

  public function __construct()
  {
    $this->id = uniqid();

    if (!isset(BaseService::$instance) || is_null(BaseService::$instance))
    {
      BaseService::$instance = $this;
    }
  }

  public static function instance(): IService|null
  {
    return BaseService::$instance;
  }
}

