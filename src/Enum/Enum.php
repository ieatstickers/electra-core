<?php

namespace Electra\Core\Enum;

use Electra\Core\Exception\ElectraException;
use Electra\Utility\Arrays;

class Enum implements \JsonSerializable
{
  private $value;

  /** @param $value */
  private function __construct($value)
  {
    $this->value = $value;
  }

  /** @return string */
  public function getValue(): string
  {
    return $this->value;
  }

  /**
   * @param string $value
   * @return Enum
   * @throws ElectraException
   */
  public static function create(string $value)
  {
    foreach (self::getConstants() as $constantName => $constantValue)
    {
      $class = get_called_class();
      if ($constantValue == $value) return new $class($value);
    }

    // throw exception saying unrecognised value
    throw (new ElectraException('Unrecognised enum value'))
      ->addMetaData('enumClass', get_called_class())
      ->addMetaData('enumValue', $value);
  }

  /**
   * @param $name
   * @param $arguments
   * @return Enum
   * @throws ElectraException
   */
  public static function __callStatic($name, $arguments)
  {
    $constantValue = Arrays::getByKey($name, self::getConstants());

    if (!$constantValue)
    {
      throw (new ElectraException('Unrecognised enum constant'))
        ->addMetaData('enumClass', get_called_class())
        ->addMetaData('enumConstant', $name);
    }

    return self::create($constantValue);
  }

  /**
   * @return array
   * @throws ElectraException
   */
  private static function getConstants(): array
  {
    try {
      $reflector = new \ReflectionClass(get_called_class());
    }
    catch (\Exception $exception)
    {
      throw (new ElectraException('Failed to retrieve enum constants'))
        ->addMetaData('enumClass', get_called_class());
    }

    return $reflector->getConstants();
  }

  /** @return string */
  public function __toString()
  {
    return $this->getValue();
  }

  /** @return string */
  public function jsonSerialize()
  {
    return $this->getValue();
  }

}