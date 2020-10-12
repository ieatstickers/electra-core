<?php

namespace Electra\Core\Event\Type;

class ClassType implements TypeInterface
{
  /** @var string */
  protected $fqns;

  /**
   * ClassType constructor.
   *
   * @param string $fqns
   */
  public function __construct(string $fqns)
  {
    $this->fqns = $fqns;
  }

  /**
   * @param string $fqns
   *
   * @return ClassType
   */
  public static function create(string $fqns)
  {
    return new static($fqns);
  }

  /**
   * @param mixed $value
   *
   * @return bool
   */
  public function validate($value): bool
  {
    return $value instanceof $this->fqns;
  }

  /** @return string */
  public function getType(): string
  {
    return $this->fqns;
  }
}
