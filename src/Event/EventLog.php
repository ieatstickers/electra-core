<?php

namespace Electra\Core\Event;

class EventLog
{
  private static $log = [];

  /** @param $data */
  public static function log($data)
  {
    self::$log[] = $data;
  }

  /** @return array */
  public static function getLogs()
  {
    return self::$log;
  }
}
