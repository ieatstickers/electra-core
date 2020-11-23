<?php

namespace Electra\Core\Event;

use Carbon\Carbon;
use Electra\Core\Context\Context;
use Electra\Core\Context\ContextAware;
use Electra\Core\Context\ContextInterface;
use Electra\Utility\Classes;

abstract class AbstractEvent implements EventInterface
{
  use ContextAware;
  protected $useStaticCache = false;
  /** @var array  */
  protected static $responseCache = [];

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
    $start = Carbon::now();

    $cacheKey = null;

    // If static caching is enabled
    if ($this->useStaticCache)
    {
      // Generate cache key
      $cacheKey = md5(json_encode($payload));

      // Set keys in response cache
      if (!isset(static::$responseCache[static::class]))
      {
        static::$responseCache[static::class] = [];
      }

      if (!isset(static::$responseCache[static::class][$cacheKey]))
      {
        static::$responseCache[static::class][$cacheKey] = null;
      }

      // If a cached response exists, return it
      if (static::$responseCache[static::class][$cacheKey])
      {
        $end = Carbon::now();
        EventLog::log([
          'event' => static::class,
          'payload' => json_encode($payload),
          'fromCache' => true,
          'timestamp' => $end->toDateTimeString(),
          'duration' => $end->diffInMilliseconds($start) . 'ms'
        ]);
        return static::$responseCache[static::class][$cacheKey];
      }
    }

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

    $eventResponse = $this->process($payload);

    // If static caching is enabled, set it
    if ($this->useStaticCache)
    {
      static::$responseCache[static::class][$cacheKey] = $eventResponse;
    }

    $end = Carbon::now();
    EventLog::log([
      'event' => static::class,
      'payload' => json_encode($payload),
      'fromCache' => false,
      'timestamp' => $end->toDateTimeString(),
      'duration' => $end->diffInMilliseconds($start) . 'ms'
    ]);

    return $eventResponse;
  }

  /**
   * @param AbstractPayload $payload
   * @return mixed
   */
  abstract protected function process(AbstractPayload $payload);
}
