<?php

namespace Assegai\Lib\Authentication;

use Assegai\Lib\Authentication\Interfaces\IAuthStrategy;

/**
 * The `Authenticator` class houses methods for issuing and managing user 
 * authentication.
 */
final class Authenticator
{
  public function __construct(
    private ?IAuthStrategy $strategy = null
  )
  {
    
  }
}

?>