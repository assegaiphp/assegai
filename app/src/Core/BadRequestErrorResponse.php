<?php

namespace LifeRaft\Core;

class BadRequestErrorResponse extends ErrorResponse
{
  public function __construct(
    protected string $message = ''
  )
  {
    parent::__construct(
      message: $message,
      status: HttpStatus::BadRequest()
    );
  }
}
