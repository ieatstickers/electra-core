<?php

namespace Electra\Core\Context;

use Electra\Core\Http\Request;

class Context
{
  /** @var Request */
  protected $request;

  /**
   * Context constructor.
   */
  protected function __construct() {}

  /**
   * @return static
   */
  public static function create()
  {
    return new static();
  }

  /**
   * @return Request
   */
  public function request(): Request
  {
    if (!$this->request)
    {
      $this->request = Request::capture();
    }

    return $this->request;
  }
}
