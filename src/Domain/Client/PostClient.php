<?php

namespace RaffleTools\Domain\Client;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Equip\Adr\DomainInterface;
use Equip\Adr\PayloadInterface;
use RaffleTools\Entity\Client;
use RaffleTools\Domain\Client\Form\Post as Form_Client;

class PostClient implements DomainInterface
{
    /**
     * @var PayloadInterface
     */
    protected $payload;

    /** @var Form_Client */
    protected $formClient;

    /** @var DoctrineObject */
    protected $doctrineObject;

    /** @var EntityManager */
    protected $entityManager;

    /**
     * @param PayloadInterface $payload
     * @param Form_Client $formClient
     * @param DoctrineObject $doctrineObject
     * @param EntityManager $entityManager
     */
    public function __construct(
        PayloadInterface $payload,
        Form_Client $formClient,
        DoctrineObject $doctrineObject,
        EntityManager $entityManager
    )
    {
        $this->payload = $payload;
        $this->entityManager = $entityManager;
        $this->formClient = $formClient;
        $this->doctrineObject = $doctrineObject;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(array $input)
    {
        try {
            $this->formClient->fill($input);
            $isValid = $this->formClient->filter();
            if ($isValid) {
                $fields = $this->formClient->getValue();
                $client = new Client();
                $this->doctrineObject->hydrate($fields, $client);
                $this->entityManager->persist($client);
                $this->entityManager->flush();
                return $this->payload
                    ->withStatus(PayloadInterface::STATUS_CREATED)
                    ->withOutput([
                        'success' => true,
                        'id' => $client->getClientId(),
                    ]);
            } else {
                $hints = $this->formClient->getMessages();
                return $this->payload
                    ->withStatus(PayloadInterface::STATUS_UNPROCESSABLE_ENTITY)
                    ->withOutput([
                        'success' => false,
                        'hints' => $hints
                    ]);
            }
        } catch (\Exception $e) {
            //TODO: implement logger and log an error here
            return $this->payload
                ->withStatus(PayloadInterface::STATUS_SERVICE_UNAVAILABLE)//is 503 the right response for this?
                ->withOutput([
                    'success' => false,
                ]);
        }
    }
}
