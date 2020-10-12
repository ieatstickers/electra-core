<?php

namespace Electra\Core\Event\Type;

class IntegerType implements TypeInterface
{
  /** @return IntegerType */
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
    return is_int($value);
  }

  /** @return string */
  public function getType(): string
  {
    return 'integer';
  }
}
