<?php

namespace Electra\Core\Event;

use Electra\Core\Exception\ElectraAccessDeniedException;
use Electra\Jwt\ElectraJwt;
use Electra\Utility\Arrays;
use Electra\Utility\Classes;
use Electra\Utility\Objects;

abstract class AbstractPayload
{
  protected function __construct() {}

  /**
   * @param array $data
   * @return static
   * @throws \Exception
   */
  public static function create($data = [])
  {
    return Objects::hydrate(new static(), (object)$data);
  }

  /** @return array */
  protected function getRequiredProperties(): array
  {
    return [];
  }

  /** @return array */
  public function getPropertyTypes(): array
  {
    return [];
  }

  /** @return bool */
  protected function validateAccess(): bool
  {
    return true;
  }

  /**
   * @return bool
   * @throws \Exception
   */
  public function validate(): bool
  {
    $propertyTypes = $this->getPropertyTypes();
    $requiredProperties = $this->getRequiredProperties();

    // For each property
    foreach ($this as $propertyName => $propertyValue)
    {
      $expectedTypeValue = Arrays::getByKey($propertyName, $propertyTypes);
      $expectedTypes = null;

      if ($expectedTypeValue)
      {
        $expectedTypes = explode('|', $expectedTypeValue);
      }

      $suppliedType = gettype($this->{$propertyName});

      // If it's null && required
      if (
        is_null($this->{$propertyName})
        && in_array($propertyName, $requiredProperties)
      )
      {
        $class = Classes::getClassName(self::class);
        throw new \Exception("Invalid payload: {$class}. Property not set: $propertyName");
      }
      // If it's not null
      elseif (!is_null($this->{$propertyName}))
      {
        if (!$expectedTypes)
        {
          continue;
        }

        $suppliedTypeIsValid = false;

        foreach ($expectedTypes as $expectedType)
        {
          if (
            // Correct class
            (
              $suppliedType == 'object'
              && ($this->{$propertyName} instanceof $expectedType)
            )
            ||
            // Correct type
            (
              $suppliedType !== 'object'
              && $suppliedType == $expectedType
            )
          )
          {
            $suppliedTypeIsValid = true;
            break;
          }
        }

        if (!$suppliedTypeIsValid)
        {
          $class = Classes::getClassName(self::class);
          $expectedType = implode('|', $expectedTypes);
          throw new \Exception("Invalid payload: {$class}. Property '$propertyName' should be of type $expectedType - $suppliedType supplied.");
        }
      }
    }

    if (!$this->validateAccess())
    {
      throw (new ElectraAccessDeniedException('Access denied'))
        ->addMetaData('payloadClass', Classes::getClassName(self::class))
        ->addMetaData('payload', json_encode($this))
        ->addMetaData('jwt', ElectraJwt::getToken());
    }

    return true;
  }
}