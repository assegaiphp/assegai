<?php

namespace LifeRaft\Core\Responses;

final class ConflictErrorResponse extends ErrorResponse
{
  public function __construct(
    protected string $message = ''
  )
  {
    parent::__construct(
      message: $message,
      status: HttpStatus::Conflict()
    );
  }
}

?>