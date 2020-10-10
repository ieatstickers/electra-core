<?php

namespace Electra\Core\Event;

use Electra\Core\Context\ContextAwareInterface;

interface EventInterface extends ContextAwareInterface
{
  /** @return string */
  public function getPayloadClass(): ?string;

  /**
   * @param AbstractPayload $payload
   * @return mixed
   * @throws \Exception
   */
  public function execute(AbstractPayload $payload);
}
