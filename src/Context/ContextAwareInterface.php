<?php

namespace Electra\Core\Context;

interface ContextAwareInterface
{
  /** @return Context */
  public function getContext();

  /**
   * @param Context $context
   *
   * @return $this
   */
  public function setContext($context);
}
