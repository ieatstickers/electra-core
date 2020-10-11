<?php

namespace Electra\Core\Context;

class Context
{
  /** @var Context */
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

  /** @return Context */
  public static function getContext(): ?Context
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
