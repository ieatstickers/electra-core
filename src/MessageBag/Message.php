<?php

namespace Electra\Core\MessageBag;

class Message
{
  /** @var string */
  private $message;
  /** @var string */
  private $type;

  /**
   * @param string $message
   * @param MessageTypeEnum $type
   * @return Message
   */
  public static function create(string $message, MessageTypeEnum $type)
  {
    $entity = new Message();
    $entity->type = $type;
    $entity->message = $message;
    return $entity;
  }

  /**
   * @param string $message
   * @return Message|string
   */
  public static function info(string $message)
  {
    return self::create($message, MessageTypeEnum::INFO());
  }

  /**
   * @param string $message
   * @return Message|string
   */
  public static function success(string $message)
  {
    return self::create($message, MessageTypeEnum::SUCCESS());
  }

  /**
   * @param string $message
   * @return Message|string
   */
  public static function warning(string $message)
  {
    return self::create($message, MessageTypeEnum::WARNING());
  }

  /**
   * @param string $message
   * @return Message|string
   */
  public static function error(string $message)
  {
    return self::create($message, MessageTypeEnum::ERROR());
  }
}