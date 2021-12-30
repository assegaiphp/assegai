<?php

namespace Assegai\Core;

use Assegai\Core\Interfaces\IController;
use Assegai\Core\Interfaces\IExecutionContext;

class ExecutionContext implements IExecutionContext
{
  
  public function __construct(
    public readonly Request $request,
    protected array $args = [],
    protected ?IController $instance = null,
    protected ?callable $callback = null,
  )
  {
    $this->request = new Request;
  }

  public function getClass(): object|string|false
  {
    return false;
  }

  public function getHandler(): callable|string|false
  {
    return false;
  }
}