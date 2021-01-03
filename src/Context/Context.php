<?php

namespace Electra\Core\Context;

class Context implements ContextInterface
{
  /** @var ContextInterface */
  protected static $context;
  /** @var string */
  protected $projectRoot;

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
  public function getProjectRoot(): string
  {
    return $this->projectRoot;
  }

  /**
   * @param string $projectRoot
   *
   * @return $this
   */
  public function setProjectRoot(string $projectRoot)
  {
    $this->projectRoot = $projectRoot;

    return $this;
  }


}
