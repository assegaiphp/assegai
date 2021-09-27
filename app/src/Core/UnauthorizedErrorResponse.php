<?php

namespace LifeRaft\Core;

class UnauthorizedErrorResponse extends ErrorResponse
{
  public function __construct(
    protected string $message = ''
  )
  {
    parent::__construct(
      message: $message,
      status: HttpStatus::Unauthorized()
    );
  }
}
