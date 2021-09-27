<?php

namespace LifeRaft\Core;

class UnauthorisedErrorResponse extends ErrorResponse
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
