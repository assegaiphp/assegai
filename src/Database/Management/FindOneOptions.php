<?php

namespace Assegai\Database\Management;

use stdClass;

/**
 * Defines the special criteria to find a specific entity.
 */
class FindOneOptions extends FindOptions
{
  /**
   * Constructs a `FindOneOptions` object
   * 
   * @param null|stdClass|array $select Specifies what columns should be retrieved.
   * @param null|stdClass|array $relations Indicates what relations of entity should be loaded (simplified left join form).
   * @param null|FindWhereOptions $where Simple condition that should be applied to match entities.
   * @param null|stdClass|array $order Order, in which entities should be ordered.
   * @param null|int $skip Skips/offests the specified number of entities.
   * @param null|int $limit Specifies the number of entities to return.
   * @param null|JoinOptions $join Specifies what relations should be loaded.
   */
  public function __construct(
    public readonly null|stdClass|array $select = null,
    public readonly null|stdClass|array $relations = null,
    public readonly ?FindWhereOptions $where = null,
    public readonly null|stdClass|array $order = null,
    public readonly ?int $skip = null,
    public readonly ?int $limit = null,
    public readonly null|array|JoinOptions $join = null,
    public readonly array $exclude = ['password'],
  ) { }
}
