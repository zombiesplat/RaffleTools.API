<?php
namespace RaffleTools\Domain\Client;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Doctrine\ORM\EntityManager;
use Equip\Adr\DomainInterface;
use Equip\Adr\PayloadInterface;
use RaffleTools\Entity\Client;

class GetClient implements DomainInterface
{
    /** @var PayloadInterface */
    private $payload;

    /** @var EntityManager */
    private $entityManager;

    /** @var DoctrineObject */
    private $doctrineObject;

    /**
     * @param PayloadInterface $payload
     * @param EntityManager $entityManager
     * @param DoctrineObject $doctrineObject
     */
    public function __construct(PayloadInterface $payload, EntityManager $entityManager, DoctrineObject $doctrineObject)
    {
        $this->payload = $payload;
        $this->entityManager = $entityManager;
        $this->doctrineObject = $doctrineObject;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(array $input)
    {
        $id = $input['id'];
        $repository = $this->entityManager->getRepository(Client::class);
        $client = $repository->find($id);
        $clientArray = $this->doctrineObject->extract($client);
        if (empty($client)) {
            return $this->payload->withStatus(PayloadInterface::STATUS_NOT_FOUND)->withOutput(['404']); //have to return output otherwise the status won't send (fixing/fixed in equip/framework v2.0)
        }
        return $this->payload
            ->withStatus(PayloadInterface::STATUS_OK)
            ->withOutput([
                'client' => $clientArray,
            ]);
    }
}
