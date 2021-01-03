<?php

namespace Electra\Core\Context;

class Context implements ContextInterface
{
  /** @var ContextInterface */
  protected static $context;
  /** @var string */
  protected static $projectRoot;

  /**
   * Context constructor.
   */
  protected function __construct() {
    static::setContext($this);
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
    return static::$context;
  }

  /**
   * @param Context $context
   */
  public static function setContext($context)
  {
    static::$context = $context;
  }

  /** @return string */
  public static function getProjectRoot(): string
  {
    return static::$projectRoot;
  }

  /**
   * @param string $projectRoot
   */
  public static function setProjectRoot($projectRoot)
  {
    static::$projectRoot = $projectRoot;
  }


}
