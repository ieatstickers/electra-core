<?php

namespace Electra\Core\Event;

use Electra\Utility\Arrays;
use Electra\Utility\Classes;

abstract class AbstractPayload
{
  /** @return $this */
  public abstract static function create();

  /** @return array */
  protected function getRequiredProperties(): array
  {
    return [];
  }

  /** @return array */
  protected function getPropertyTypes(): array
  {
    return [];
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

    return true;
  }
}