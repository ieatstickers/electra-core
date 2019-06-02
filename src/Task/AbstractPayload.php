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
        $propertyType = Arrays::getByKey($propertyName, $propertyTypes);

        if (
          // Not correct class
          (
            gettype($this->{$propertyName}) == 'object'
            && !($this->{$propertyName} instanceof $propertyType)
          )
          ||
          // Not correct type
          (
            gettype($this->{$propertyName}) !== 'object'
            && gettype($this->{$propertyName}) !== $propertyType
          )
        )
        {
          $class = Classes::getClassName(self::class);
          $propertyType = gettype($this->{$propertyName}) == 'object' ? get_class($this->{$propertyName}) : gettype($this->{$propertyName});
          throw new \Exception("Invalid payload: {$class}. Property '$propertyName' should be of type $propertyType - $propertyType supplied.");
        }

      }
    }

    return true;
  }
}