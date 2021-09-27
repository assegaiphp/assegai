<?php

namespace LifeRaft\Core;

class Response
{
  protected int $total = 0;

  public function __construct(
    protected mixed                  $data = null,
    protected int                    $limit = 100,
    protected int                    $skip = 0,
    protected ResponseType|null      $type = null,
    protected bool                   $data_only = false,
    protected HttpStatusCode|null    $status = null
  ) {
    if (is_null($this->type()))
    {
      $this->type = ResponseType::JSON();
    }
    if (is_null($this->status))
    {
      $this->status = HttpStatus::OK();
    }

    header("Content-Type: {$this->type()}");
    http_response_code($this->status()->code());
    $this->data(data: $data);
  }

  public function total(): int
  {
    return $this->total;
  }

  public function limit(): int
  {
    return $this->limit;
  }

  public function skip(): int
  {
    return $this->skip;
  }

  public function data(mixed $data = NULL): mixed
  {
    if (!is_null($data))
    {
      $this->data = $data;
      $this->total = is_countable($this->data) ? count($this->data) : 1;
    }

    return $this->data;
  }

  public function status(HttpStatus|null $status = null): HttpStatusCode
  {
    if (!is_null($status))
    {
      $this->status = $status;
    }

    return $this->status;
  }

  public function type(): ResponseType
  {
    return $this->type;
  }

  public function __toString(): string
  {
    return $this->to_json();
  }

  public function __serialize(): array
  {
    return $this->to_array();
  }

  public function to_array(): array
  {
    return [
      'total' => $this->total(),
      'limit' => $this->limit(),
      'skip'  => $this->skip(),
      'data'  => $this->data(),
    ];
  }

  public function to_json(): string
  {
    if ($this->data_only)
    {
      return json_encode( $this->data() );
    }

    if ($this->total() === 1)
    {
      return json_encode( is_array($this->data()) ? $this->data()[0] : $this->data() );
    }

    return json_encode($this->to_array());
  }
}

?>