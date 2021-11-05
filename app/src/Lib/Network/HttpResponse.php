<?php

namespace LifeRaft\Lib\Network;

use LifeRaft\Core\Responses\HttpStatus;
use LifeRaft\Core\Responses\HttpStatusCode;

final class HttpResponse
{
  public function __construct(
    private mixed $value,
    private array $errors = [],
    private bool $isOK = true,
    private ?HttpStatusCode $status = null
  )
  {
    if (is_null($this->status))
    {
      $this->status = HttpStatus::OK();
    }
    http_response_code(response_code: $this->status->code());
  }

  public function isOK(): bool
  {
    return $this->isOK;
  }

  public function isError(): bool
  {
    return $this->isOK === false;
  }

  public function value(): mixed
  {
    return $this->isOK ? $this->value : $this->errors;
  }

  public function toArray(): array
  {
    return [
      'isOK'    => $this->isOK(),
      'value'   => $this->value(),
      'errors'  => $this->errors
    ];
  }

  public function toJSON(): string
  {
    return json_encode($this->value());
  }

  public function __toString(): string
  {
    return $this->toJSON();    
  }

  public function contains(mixed $needle): bool
  {
    return in_array(needle: $needle, haystack: $this->value, strict: true);
  }

  public function containsError(mixed $needle): bool
  {
    return in_array(needle: $needle, haystack: $this->errors, strict: true);
  }
}

?>