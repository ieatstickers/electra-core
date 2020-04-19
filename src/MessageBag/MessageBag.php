<?php

namespace Electra\Core\MessageBag;

use Electra\Dal\Data\Collection;

class MessageBag
{
  /** @var Collection */
  private static $messages;

  /**
   * @param Message $message
   * @return Collection
   */
  public static function addMessage(Message $message): Collection
  {
    if (!self::$messages)
    {
      self::$messages = new Collection();
    }

    self::$messages->add($message);

    return self::$messages;
  }

  /** @return Collection */
  public static function getAllMessages(): Collection
  {
    return self::$messages ?: new Collection();
  }
}