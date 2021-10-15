<?php

namespace LifeRaft\Core\Responses;

class NotFoundErrorResponse extends ErrorResponse
{
  public function __construct(
    protected string $message = ''
  )
  {
    parent::__construct(
      message: $message,
      status: HttpStatus::NotFound()
    );
  }
}
