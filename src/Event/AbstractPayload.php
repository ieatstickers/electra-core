<?php

namespace Electra\Core\Event;

use Electra\Utility\Arrays;
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
        throw new \Exception("Invalid payload: Property not set: $propertyName");
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
          $expectedType = implode('|', $expectedTypes);
          throw new \Exception("Invalid payload. Property '$propertyName' should be of type $expectedType - $suppliedType supplied.");
        }
      }
    }

    return true;
  }
}