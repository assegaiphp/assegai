<?php

namespace Assegai\Database\Management;

use stdClass;

/**
 *  * Used to specify what entity relations should be loaded.
 *
 * Example:
 *  JoinOptions $options = new JoinOptions(
 *     alias: "photo",
 *     leftJoin: [
 *         "author" => "photo.author",
 *         "categories" => "categories",
 *         "user" => "categories.user",
 *         "profile" => "user.profile"
 *     ],
 *     innerJoin: [
 *         "author" => "photo.author",
 *         "categories" => "categories",
 *         "user" => "categories.user",
 *         "profile" => "user.profile"
 *     ],
 *     leftJoinAndSelect: [
 *         "author" => "photo.author",
 *         "categories" => "categories",
 *         "user" => "categories.user",
 *         "profile" => "user.profile"
 *     ],
 *     innerJoinAndSelect: [
 *         "author" => "photo.author",
 *         "categories" => "categories",
 *         "user" => "categories.user",
 *         "profile" => "user.profile"
 *     ]
 * };
 */
class JoinOptions
{
  /**
   * Constructs the `JoinOptions`
   * 
   * @param null|string $alias Alias of the main entity.
   * @param null|stdClass|array $leftJoinAndSelect Array of columns to LEFT JOIN.
   * @param null|stdClass|array $innerJoinAndSelect Array of columns to INNER JOIN.
   * @param null|stdClass|array $leftJoin Array of columns to LEFT JOIN.
   * @param null|stdClass|array $innerJoin Array of columns to INNER JOIN.
   */
  public function __construct(
    public readonly ?string $alias,
    public readonly null|stdClass|array $leftJoinAndSelect,
    public readonly null|stdClass|array $innerJoinAndSelect,
    public readonly null|stdClass|array $leftJoin,
    public readonly null|stdClass|array $innerJoin,
  ) { }
}