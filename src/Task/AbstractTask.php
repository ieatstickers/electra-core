<?php

namespace Electra\Core\Task;

use Electra\Utility\Classes;

abstract class AbstractTask
{
  /** @return string */
  abstract public function getPayloadClass(): string;

  /**
   * @param AbstractPayload $payload
   * @return mixed
   * @throws \Exception
   */
  public final function execute(AbstractPayload $payload)
  {
    // If incorrect payload class has been passed in
    if (get_class($payload) !== $this->getPayloadClass())
    {
      $payloadClassName = Classes::getClassName(get_class($payload));
      $taskClassName = Classes::getClassName(self::class);

      throw new \Exception("Invalid payload passed to {$taskClassName}. Expected {$this->getPayloadClass()}, got {$payloadClassName}");
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