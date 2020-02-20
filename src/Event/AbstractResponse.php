<?php

namespace Electra\Core\Event;

use Electra\Utility\Objects;

abstract class AbstractResponse
{
  protected function __construct() {}

  /** @return string */
  public function serialize(): string
  {
    return json_encode($this);
  }

  /**
   * @param array $data
   * @return mixed|object|null
   * @throws \Exception
   */
  public static function create($data = [])
  {
    return Objects::hydrate(new static(), (object)$data);
  }
}