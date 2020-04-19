<?php

namespace Electra\Core\MessageBag;

use Electra\Core\Enum\Enum;

/**
 * @method static $this INFO
 * @method static $this SUCCESS
 * @method static $this WARNING
 * @method static $this ERROR
 */
class MessageTypeEnum extends Enum
{
  /** @var string */
  const INFO = 'info';
  /** @var string */
  const SUCCESS = 'success';
  /** @var string */
  const WARNING = 'warning';
  /** @var string */
  const ERROR = 'error';

  /** @return bool */
  public function isInfo(): bool
  {
    return $this->getValue() == self::INFO;
  }

  /** @return bool */
  public function isSuccess(): bool
  {
    return $this->getValue() == self::SUCCESS;
  }

  /** @return bool */
  public function isWarning(): bool
  {
    return $this->getValue() == self::WARNING;
  }

  /** @return bool */
  public function isError(): bool
  {
    return $this->getValue() == self::ERROR;
  }

}