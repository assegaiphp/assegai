<?php

namespace Assegai\Database\Queries;

class FindOptions
{
  /**
   * @param array $select Indicates which properties of the main object must be selected.
   * @param array $relations Specifies the relations that need to be loaded with the main entity. Sub-relations can also be loaded (shorthand for join and leftJoinAndSelect)
   * @param array $join Specifies the joins that need to be performed for the entity. Extended version of "relations".
   * @param array $exclude Indicates which properties of the main object must be excluded.
   */
  public function __construct(
    public readonly array $select = [],
    public readonly array $relations = [],
    public readonly array $join = [],
    public readonly array $exclude = ['password'],
  ) { }
}