<?php

namespace Assegai\Database\Management;

class RemoveOptions
{
  /**
   * Constructs the RemoveOptions.
   * 
   * @param mixed $data Additional data to be passed with remove method.
   * This data can be used in subscribers then.
   * 
   * @param null|bool $listeners Indicates if listeners and subscribers are called for this operation.
   * By default they are enabled, you can disable them by setting { listeners: false } in save/remove options.
   *
   * @param null|bool $transaction By default transactions are enabled and all queries in persistence operation are wrapped into the transaction.
   * You can disable this behaviour by setting { transaction: false } in the persistence options.
   *
   * @param null|int $chunk Breaks save execution into given number of chunks.
   * For example, if you want to save 100,000 objects but you have issues with saving them,
   * you can break them into 10 groups of 10,000 objects (by setting { chunk: 10000 }) and save each group separately.
   * This option is needed to perform very big insertions when you have issues with underlying driver parameter number limitation.
   */
  public function __construct(
    public readonly ?mixed $data = null,
    public readonly ?bool $listeners = true,
    public readonly ?bool $transaction = false,
    public readonly ?int $chunk = null,
  ) { }
}