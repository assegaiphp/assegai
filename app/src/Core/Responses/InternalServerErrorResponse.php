<?php

namespace Assegai\Core\Responses;

class InternalServerErrorResponse extends ErrorResponse
{
  public function __construct(
    protected string $message = ''
  )
  {
    parent::__construct(
      message: $message,
      status: HttpStatus::InternalServerError()
    );
  }
}
