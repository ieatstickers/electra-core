<?php

namespace Electra\Core\Event;

abstract class AbstractResponse
{
  protected function __construct() {}

  /** @return string */
  public function serialize(): string
  {
    return json_encode($this);
  }

  /** @return $this */
  public abstract static function create();
}