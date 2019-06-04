<?php

namespace Electra\Core\Task;

use Electra\Utility\Arrays;
use Electra\Utility\Classes;

abstract class AbstractPayload
{
  /**
   * @return array
   */
  protected function getRequiredProperties(): array
  {
    return [];
  }

  /**
   * @return array
   */
  protected function getPropertyTypes(): array
  {
    return [];
  }

  /**
   * @return bool
   * @throws \Exception
   */
  public final function validate(): bool
  {
    $propertyTypes = $this->getPropertyTypes();
    $requiredProperties = $this->getRequiredProperties();

    // For each property
    foreach ($this as $propertyName => $propertyValue)
    {
      $expectedType = Arrays::getByKey($propertyName, $propertyTypes);
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
        if (!$expectedType)
        {
          continue;
        }

        if (
          // Not correct class
          (
            $suppliedType == 'object'
            && !($this->{$propertyName} instanceof $expectedType)
          )
          ||
          // Not correct type
          (
            $suppliedType !== 'object'
            && $suppliedType !== $expectedType
          )
        )
        {
          $class = Classes::getClassName(self::class);
          throw new \Exception("Invalid payload: {$class}. Property '$propertyName' should be of type $expectedType - $suppliedType supplied.");
        }

      }
    }

    return true;
  }
}