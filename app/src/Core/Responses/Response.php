<?php

namespace LifeRaft\Core\Responses;

use LifeRaft\Core\Request;
use LifeRaft\Core\Result;

class Response
{
  protected int $total = 0;
  protected Request $request;

  public function __construct(
    protected mixed                   $data = null,
    protected int                     $limit = 100,
    protected int                     $skip = 0,
    protected ?ResponseType           $type = null,
    protected bool                    $dataOnly = false,
    protected ?HttpStatusCode         $status = null
  ) {
    $this->request = Request::instance();

    if (is_null($this->type()))
    {
      $this->type = ResponseType::JSON();
    }

    $this->limit = $this->request->limit();
    $this->skip = $this->request->skip();

    if (!headers_sent())
    {
      header("Content-Type: {$this->type()}");
    }

    if (!is_null($this->status))
    {
      http_response_code($this->status()->code());
    }
    $this->setData(data: $data);
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
    return !is_null($this->data) ? $this->data : [];
  }

  public function setData(mixed $data): void
  {
    $this->data = $data;

    if (!is_null($this->data))
    {
      $this->total = is_countable($this->data) ? count($this->data) : 1;
    }
  }

  public function status(): HttpStatusCode
  {
    return is_null($this->status) ? HttpStatus::OK() : $this->status;
  }

  public function setStatus(HttpStatus $status): void
  {
    if (!is_null($status))
    {
      $this->status = $status;
      http_response_code( response_code: $this->status()->code());
    }
  }

  public function type(): ?ResponseType
  {
    return $this->type;
  }

  public function __toString(): string
  {
    return $this->toJSON();
  }

  public function __serialize(): array
  {
    return $this->toArray();
  }

  public function toArray(): array
  {
    return [
      'total' => $this->total(),
      'limit' => $this->limit(),
      'skip'  => $this->skip(),
      'data'  => $this->data(),
    ];
  }

  public function toJSON(): string
  {
    if ($this->dataOnly)
    {
      $data =
        is_array($this->data()) && count($this->data()) === 1 
        ? $this->data()[0]
        : $this->data();
      return json_encode( $data );
    }

    if ($this->total() === 1)
    {
      return json_encode( ! is_array($this->data()) ? $this->data() : $this->data()[array_key_first( $this->data() )] );
    }

    return json_encode($this->toArray());
  }
}

?>