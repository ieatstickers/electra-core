<?php

namespace Electra\Core\Context;

trait ContextAware
{
  /** @var ContextInterface */
  private $context;

  /** @return ContextInterface */
  public function getContext(): ContextInterface
  {
    return $this->context;
  }

  /**
   * @param ContextInterface $context
   *
   * @return $this
   */
  public function setContext($context)
  {
    $this->context = $context;

    return $this;
  }
}
