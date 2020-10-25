<?php

namespace Electra\Core\Event\Type;

/**
 * Class Type
 *
 * @package Electra\Core\Event\Type
 */
class Type
{
  /** @return StringType */
  public static function string()
  {
    return StringType::create();
  }

  /** @return BooleanType */
  public static function boolean()
  {
    return BooleanType::create();
  }

  /** @return IntegerType */
  public static function integer()
  {
    return IntegerType::create();
  }

  /** @return FloatType */
  public static function float()
  {
    return FloatType::create();
  }

  /**
   * @param TypeInterface $arrayItemType
   *
   * @return ArrayType
   */
  public static function array(TypeInterface $arrayItemType = null)
  {
    return ArrayType::create($arrayItemType);
  }

  /**
   * @param string $fqns
   *
   * @return ClassType
   */
  public static function class(string $fqns)
  {
    return ClassType::create($fqns);
  }
}
