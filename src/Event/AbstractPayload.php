<?php

namespace Electra\Core\Event;

use Electra\Core\Event\Type\TypeInterface;
use Electra\Core\Exception\ElectraException;
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

  /** @return TypeInterface[] */
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

    // If any of the required properties are missing, throw an exception
    foreach ($requiredProperties as $requiredProperty)
    {
      if (!isset($this->{$requiredProperty}))
      {
        throw (new ElectraException("Invalid payload - missing property."))
          ->addMetaData('property', $requiredProperty);
      }
    }

    // If any of the properties present are not the correct type, throw an exeption
    foreach ($this as $propertyName => $propertyValue)
    {
      $expectedTypeValue = Arrays::getByKey($propertyName, $propertyTypes);
      $expectedTypes = null;

      if ($expectedTypeValue)
      {
        /** @var TypeInterface[] $expectedTypes */
        $expectedTypes = is_array($expectedTypeValue) ? $expectedTypeValue : [$expectedTypeValue];
      }

      if (is_null($propertyValue) || !$expectedTypes)
      {
        continue;
      }

      $suppliedTypeIsValid = false;

      foreach ($expectedTypes as $expectedType)
      {
        if ($expectedType->validate($propertyValue))
        {
          $suppliedTypeIsValid = true;
          break;
        }
      }

      if (!$suppliedTypeIsValid)
      {
        $displayableTypes = [];

        foreach ($expectedTypes as $type)
        {
          $displayableTypes[] = $type->getType();
        }

        $expectedType = implode(' | ', $displayableTypes);
        $suppliedType = gettype($propertyValue);

        throw new \Exception("Invalid payload. Property '$propertyName' should be of type $expectedType - $suppliedType supplied.");
      }
    }

    return true;
  }
}
