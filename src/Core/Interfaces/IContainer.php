<?php

namespace Assegai\Core\Interfaces;

interface IContainer
{
  /**
   * Finds an entry of the container by its identifier and returns it.
   * 
   * @param string $entryIdentifier Identifier of the entry to look for.
   * 
   * @return mixed Returns anything (a mixed value).
   * @throws INotFoundException If the identifier is not known to the container.
   * @throws IContainerException Error while retrieving the entry.
   */
  public function get(string $entryIdentifier): mixed;

  /**
   * Returns `true` if the container can return an entry for the given identifier, `false` otherwise.
   * 
   * @param string $entryIdentifier Identifier of the entry to look for.
   * 
   * @return bool Returns `true` if the container can return an entry for the given identifier, `false` otherwise.
   * @throws INotFoundException If the identifier is not known to the container.
   */
  public function has(string $entryIdentifier): bool;
}