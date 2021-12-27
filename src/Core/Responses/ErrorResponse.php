<?php

namespace Assegai\Core\Responses;

use JetBrains\PhpStorm\ArrayShape;

class ErrorResponse extends Response
{
    public function __construct(
        protected string $message,
        protected string|array|null $error = null,
        protected mixed $data = null,
        protected ResponseType|null $type = null,
        protected HttpStatusCode|null $status = null
    )
    {
        if (is_null($type))
        {
            $this->type = ResponseType::JSON();
        }

        parent::__construct( data: $this->data, type: $this->type, status: $this->status );
        if (empty($message))
        {
        $this->message = $this->status()->name();
        }
    }

    public function message(): string
    {
        return $this->message;
    }

    #[ArrayShape(['message' => "string", 'data' => "mixed", 'status' => "\Assegai\Core\Responses\HttpStatusCode"])]
    public function toArray(): array
    {
        return [
            'message' => $this->message(),
            'data' => $this->data(),
            'status' => $this->status(),
        ];
    }
}

