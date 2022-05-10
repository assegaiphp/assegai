<?php

namespace Assegai\Core\Injection;

class InjectionToken
{
  /**
   * Constructs an InjectionToken.
   * 
   * @param string $description Description for the token, used only for 
   * debugging purposes, it should but does not need to be unique
   * @param InjectionTokenOptions $options Options for the token's usage, as described above
   */
  public function __construct(
    protected readonly string $description,
    public ?InjectionTokenOptions $options = null
  )
  {
    if (empty($this->options))
    {
      $this->options = new InjectionTokenOptions(type: '');
    }
  }
}