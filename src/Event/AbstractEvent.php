<?php

namespace Electra\Core\Event;

use Electra\Acl\Event\Access\GetAll\GetAllAccessPayload;
use Electra\Acl\Event\ElectraAclEvents;
use Electra\Core\Exception\ElectraInvalidPayloadException;
use Electra\Jwt\ElectraJwt;
use Electra\Utility\Classes;

abstract class AbstractEvent implements EventInterface
{
  /** @return string */
  abstract public function getPayloadClass(): string;

  /**
   * @param AbstractPayload $payload
   * @return mixed
   * @throws \Exception
   */
  public function execute(AbstractPayload $payload)
  {
    // If incorrect payload class has been passed in
    if (get_class($payload) !== $this->getPayloadClass())
    {
      $payloadClassName = Classes::getClassName(get_class($payload));
      $eventClassName = Classes::getClassName(self::class);

      throw new \Exception("Invalid payload passed to {$eventClassName}. Expected {$this->getPayloadClass()}, got {$payloadClassName}");
    }

    // Validate the payload - will throw an exception if required fields and types are not set
    $payload->validate();

    return $this->process($payload);
  }

  /**
   * @param int $resourceId
   * @param string $resourceType
   * @return bool
   * @throws \Exception
   */
  protected function hasAccess(int $resourceId, string $resourceType): bool
  {
    $token = ElectraJwt::getToken();

    if (!$token)
    {
      throw (new ElectraInvalidPayloadException('No token found. Event cannot continue without an authenticated user.'))
        ->addMetaData('payloadClass', Classes::getClassName(self::class))
        ->addMetaData('payload', json_encode($this));
    }

    if ($token->getRequiredClaim('ownerType') == 'system')
    {
      return true;
    }

    $accessPayload = GetAllAccessPayload::create();
    $accessPayload->ownerId = $token->getRequiredClaim('ownerId');
    $accessPayload->ownerType = $token->getRequiredClaim('ownerType');

    $accessResponse = ElectraAclEvents::getAllAccess($accessPayload);

    return $accessResponse->hasAccess(
      $resourceId,
      $resourceType
    );
  }

  /**
   * @param AbstractPayload $payload
   * @return mixed
   */
  abstract protected function process(AbstractPayload $payload);
}