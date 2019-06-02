<?php

namespace Electra\Core\Task;

abstract class AbstractResponse
{
  /** @return string */
  public function serialize(): string
  {
    return json_encode($this);
  }
}