<?php

namespace LifeRaft\Database\Queries;

final class SQLQueryResult
{
  public function __construct(
    private array $data,
    private array $errors = [],
    private bool $isOK = true,
  )
  {
  }

  public function isOK(): bool
  {
    return $this->isOK;
  }

  public function isError(): bool
  {
    return $this->isOK === false;
  }

  public function value(): array
  {
    return $this->isOK ? $this->data : $this->errors;
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
    return in_array(needle: $needle, haystack: $this->data, strict: true);
  }

  public function containsError(mixed $needle): bool
  {
    return in_array(needle: $needle, haystack: $this->errors, strict: true);
  }
}

?>