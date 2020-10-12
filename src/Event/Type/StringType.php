<?php

namespace Electra\Core\Event\Type;

class StringType implements TypeInterface
{
  /** @return StringType */
  public static function create()
  {
    return new static();
  }

  /**
   * @param mixed $value
   *
   * @return bool
   */
  public function validate($value): bool
  {
    return is_string($value);
  }

  /** @return string */
  public function getType(): string
  {
    return 'string';
  }
}
