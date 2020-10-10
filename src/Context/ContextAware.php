<?php

namespace Electra\Core\Context;

trait ContextAware
{
  /** @var Context */
  private $context;

  public function getContext()
  {
    return $this->context;
  }

  /**
   * @param Context $context
   *
   * @return $this
   */
  public function setContext($context)
  {
    $this->context = $context;

    return $this;
  }
}
