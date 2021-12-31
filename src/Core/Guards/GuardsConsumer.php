<?php

namespace Assegai\Core\Guards;

use Assegai\Core\Enumerations\TypeContext;
use Assegai\Core\ExecutionContext;
use Assegai\Core\Interfaces\ICanActivate;
use Assegai\Core\Interfaces\IController;
use Assegai\Core\Request;

class GuardsConsumer
{
  public function tryActivate(
    array $guards,
    array $args,
    IController $controller,
    ?callable $callback = null,
    TypeContext $type = TypeContext::HTTP
  ): bool
  {
    if (empty($guards))
    {
      return true;
    }
    $context = $this->createContext(args: $args, instance: $controller, callback: $callback);

    foreach ($guards as $guard)
    {
      if ($guard instanceof ICanActivate)
      {
        $result = $guard->canActivate(context: $context);
        if ($this->pickResult(result: $result))
        {
          continue;
        }

        return false;
      }
    }

    return true;
  }

  protected function createContext(array $args, IController $instance, callable $callback): ExecutionContext
  {
    return new ExecutionContext(request: new Request, args: $args, instance: $instance, callback: $callback);
  }

  protected function pickResult(bool $result): bool
  {
    return $result;
  }
}