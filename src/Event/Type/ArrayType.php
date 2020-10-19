<?php

namespace Electra\Core\Event\Type;

class ArrayType implements TypeInterface
{
  /** @var TypeInterface */
  protected $arrayItemType;

  /**
   * ArrayType constructor.
   *
   * @param TypeInterface $arrayItemType
   */
  public function __construct(TypeInterface $arrayItemType = null)
  {
    $this->arrayItemType = $arrayItemType;
  }

  /**
   * @param mixed $value
   *
   * @return array
   */
  public function cast($value)
  {
    $initialValue = $value;

    if (is_string($value))
    {
      $value = json_decode($value, true);
    }

    if (!is_array($value))
    {
      return $initialValue;
    }

    if ($this->arrayItemType)
    {
      foreach ($value as $key => $itemValue)
      {
        $value[$key] = $this->arrayItemType->cast($itemValue);
      }
    }

    return $value;
  }

  /**
   * @param TypeInterface|null $arrayItemType
   *
   * @return ArrayType
   */
  public static function create(TypeInterface $arrayItemType = null)
  {
    return new static($arrayItemType);
  }

  /**
   * @param mixed $value
   *
   * @return bool
   */
  public function validate($value): bool
  {
    if (!is_array($value))
    {
      return false;
    }

    // If here, we know $value is an array
    // If no type for items, validation is successful
    if (!$this->arrayItemType)
    {
      return true;
    }

    // If any of the properties are not of the correct type, return false
    foreach ($value as $itemValue)
    {
      if (!$this->arrayItemType->validate($itemValue))
      {
        return false;
      }
    }

    return true;
  }

  /** @return string */
  public function getType(): string
  {
    $type = $this->arrayItemType ? $this->arrayItemType->getType() : 'any';
    return "array[{$type}]";
  }
}
