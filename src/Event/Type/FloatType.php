<?php

namespace Electra\Core\Event\Type;

class FloatType implements TypeInterface
{
  /** @return FloatType */
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
    return is_float($value);
  }

  /** @return string */
  public function getType(): string
  {
    return 'float';
  }
}
