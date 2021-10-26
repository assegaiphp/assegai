<?php

namespace LifeRaft\Core\Responses;

use LifeRaft\Core\Request;

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

    return !is_null($this->data) ? $this->data : [];
  }

  public function status(?HttpStatus $status = null): ?HttpStatusCode
  {
    if (!is_null($status))
    {
      $this->status = $status;
    }

    return $this->status;
  }

  public function type(): ?ResponseType
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
    if ($this->dataOnly)
    {
      return json_encode( $this->data() );
    }

    if ($this->total() === 1)
    {
      return json_encode( ! is_array($this->data()) ? $this->data() : $this->data()[array_key_first( $this->data() )] );
    }

    return json_encode($this->to_array());
  }
}

?>