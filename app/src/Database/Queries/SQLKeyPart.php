<?php

namespace LifeRaft\Database\Queries;

final class SQLKeyPart
{
  private string $queryString = '';
  /**
   * @param string $key The name of the key part
   * @param bool|null $ascending Sort specifier. If set to `true`, appends 
   * `ASC` to resulting SQL. If set to `false`, appends `DESC` to 
   * resulting SQL. If set to `null` then ommits sorting string.
   */
  public function __construct(
    private string $key,
    private ?bool $ascending = null
  )
  {
    $this->sql = "$this->key";
    if (!is_null($this->ascending))
    {
      $this->sql .= $this->ascending ? ' ASC' : ' DESC';
    }
  }

  public function __toString(): string
  {
    return $this->sql;
  }
}

?>