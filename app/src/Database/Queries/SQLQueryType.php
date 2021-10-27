<?php

namespace LifeRaft\Database\Queries;

final class SQLQueryType
{
  const SELECT    = 'SELECT';
  const INSERT    = 'INSERT';
  const UPDATE    = 'UPDATE';
  const DELETE    = 'DELETE';
  const DROP      = 'DROP';
  const TRUNCATE  = 'TRUNCATE';
  const CREATE    = 'CREATE';
  const USE       = 'USE';
  const EXPLAIN   = 'EXPLAIN';
  const DESCRIBE  = 'DESCRIBE';
  const DESC      = 'DESCRIBE';
}

?>