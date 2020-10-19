<?php

namespace Electra\Core\Event\Type;

interface TypeInterface
{
  /**
   * @param mixed $value
   *
   * @return mixed
   */
  public function cast($value);

  /**
   * @param mixed $value
   *
   * @return bool
   */
  public function validate($value): bool;

  /** @return string */
  public function getType(): string;
}
