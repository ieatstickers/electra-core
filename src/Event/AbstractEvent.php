<?php

namespace Electra\Core\Event;

use Electra\Utility\Classes;

abstract class AbstractEvent implements EventInterface
{
  /** @return string */
  abstract public function getPayloadClass(): string;

  /**
   * @param AbstractPayload $payload
   * @return mixed
   * @throws \Exception
   */
  public function execute(AbstractPayload $payload)
  {
    // If incorrect payload class has been passed in
    if (get_class($payload) !== $this->getPayloadClass())
    {
      $payloadClassName = Classes::getClassName(get_class($payload));
      $eventClassName = Classes::getClassName(self::class);

      throw new \Exception("Invalid payload passed to {$eventClassName}. Expected {$this->getPayloadClass()}, got {$payloadClassName}");
    }

    // Validate the payload - will throw an exception if required fields and types are not set
    $payload->validate();

    return $this->process($payload);
  }

  /**
   * @param AbstractPayload $payload
   * @return mixed
   */
  abstract protected function process(AbstractPayload $payload);
}