<?php

namespace Electra\Core\Context;

interface ContextAwareInterface
{
  /** @return ContextInterface */
  public function getContext();

  /**
   * @param ContextInterface $context
   *
   * @return $this
   */
  public function setContext($context);
}
