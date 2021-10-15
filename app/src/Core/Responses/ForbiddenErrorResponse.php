<?php

namespace LifeRaft\Core\Responses;

class ForbiddenErrorResponse extends ErrorResponse
{
  public function __construct(
    protected string $message = ''
  )
  {
    parent::__construct(
      message: $message,
      status: HttpStatus::Forbidden()
    );
  }
}
