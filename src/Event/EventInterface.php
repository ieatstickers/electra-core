<?php

namespace Electra\Core\Event;

interface EventInterface
{
  /** @return string */
  public function getPayloadClass(): string;
  
  /**
   * @param AbstractPayload $payload
   * @return mixed
   * @throws \Exception
   */
  public function execute(AbstractPayload $payload);
}