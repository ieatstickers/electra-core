<?php

namespace Electra\Core\Event;

use Electra\Core\Context\Context;
use Electra\Core\Context\ContextAware;
use Electra\Core\Context\ContextInterface;
use Electra\Utility\Classes;

abstract class AbstractEvent implements EventInterface
{
  use ContextAware;

  /** @return ContextInterface|null */
  public function getContext()
  {
    if(!$this->context)
    {
      $this->context = Context::getContext();
    }

    return $this->context;
  }

  /** @return string */
  public function getPayloadClass(): ?string
  {
    return null;
  }

  /**
   * @param AbstractPayload $payload
   * @return mixed
   * @throws \Exception
   */
  public function execute($payload)
  {
    // If incorrect payload class has been passed in
    if ($this->getPayloadClass())
    {
      $eventClassName = Classes::getClassName(self::class);

      if (!$payload)
      {
        throw new \Exception("No payload passed to {$eventClassName}. Expected {$this->getPayloadClass()}.");
      }

      if (get_class($payload) !== $this->getPayloadClass())
      {
        $payloadClassName = Classes::getClassName(get_class($payload));

        throw new \Exception("Invalid payload passed to {$eventClassName}. Expected {$this->getPayloadClass()}, got {$payloadClassName}");
      }

      // Validate the payload - will throw an exception if required fields and/or types are not set
      $payload->validate();
    }


    return $this->process($payload);
  }

  /**
   * @param AbstractPayload $payload
   * @return mixed
   */
  abstract protected function process(AbstractPayload $payload);
}
