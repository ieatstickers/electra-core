<?php

namespace Electra\Core\Exception;

class ElectraException extends \Exception
{

  /** @var string */
  protected $displayMessage;

  /** @var array */
  protected $metaData = [];

  /**
   * Exception constructor.
   * @param string $message
   * @param string $displayMessage
   */
  public function __construct(
    string $message,
    string $displayMessage = 'Something went wrong. Please try again.'
  )
  {
    $this->displayMessage = $displayMessage;
    parent::__construct($message);
  }

  /** @return string */
  public function getDisplayMessage(): string
  {
    return $this->displayMessage;
  }

  /**
   * @param $key
   * @param $value
   * @return self
   */
  public function addMetaData($key, $value)
  {
    $this->metaData[$key] = $value;

    return $this;
  }

  /** @return array */
  public function getMetaData(): array
  {
    return $this->metaData;
  }
}