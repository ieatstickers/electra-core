<?php

namespace Electra\Core\Event\Type;

class BooleanType implements TypeInterface
{
  /** @return BooleanType */
  public static function create()
  {
    return new static();
  }

  /**
   * @param mixed $value
   *
   * @return string
   */
  public function cast($value)
  {
    if (
      is_bool($value)
      || $value == 'true'
      || $value == 1
      || $value == 0
      || $value == '1'
      || $value == '0'
    )
    {
      return (bool)$value;
    }
    else if ($value == 'false')
    {
      return false;
    }

    return $value;
  }

  /**
   * @param mixed $value
   *
   * @return bool
   */
  public function validate($value): bool
  {
    return is_bool($value);
  }

  /** @return string */
  public function getType(): string
  {
    return 'boolean';
  }
}
