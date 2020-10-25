<?php

namespace Electra\Core\Context;

class Context implements ContextInterface
{
  /** @var ContextInterface */
  private static $context;

  /**
   * Context constructor.
   */
  protected function __construct() {
    Context::setContext($this);
  }

  /**
   * @return static
   */
  public static function create()
  {
    return new static();
  }

  /** @return ContextInterface */
  public static function getContext(): ?ContextInterface
  {
    return self::$context;
  }

  /**
   * @param Context $context
   */
  public static function setContext($context)
  {
    self::$context = $context;
  }

}
