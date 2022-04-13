<?php

namespace Assegai\Database\Management;

use stdClass;

class FindOptions
{
  public function __construct(
    public readonly null|stdClass|array $select = null,
    public readonly null|stdClass|array $relations = null,
    public readonly ?FindWhereOptions $where = null,
    public readonly null|stdClass|array $order = null,
    public readonly ?int $skip = null,
    public readonly ?int $limit = null,
    public readonly array $exclude = ['password'],
  ) { }

  public function __toString(): string
  {
    $output = strval($this->where);

    if (!empty($limit))
    {
      $output .= " LIMIT $limit";

      if (!empty($skip))
      {
        $output .= " OFFSET $skip";
      }
    }

    return trim($output);
  }
}