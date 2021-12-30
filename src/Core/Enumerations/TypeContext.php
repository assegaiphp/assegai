<?php

namespace Assegai\Core\Enumerations;

enum TypeContext: string
{
  case HTTP         = 'http';
  case GRPC         = 'grpc';
  case GRAPHQL      = 'graphql';
  case WEB_SOCKETS  = 'ws';
}