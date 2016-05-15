<?php
namespace RaffleTools\Domain\RaffleItem;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Equip\Adr\DomainInterface;
use Equip\Adr\PayloadInterface;
use RaffleTools\Entity\RaffleItem;
use RaffleTools\Domain\RaffleItem\Form\Post as Form_RaffleItem;

class PostRaffleItem implements DomainInterface
{
    /**
     * @var PayloadInterface
     */
    protected $payload;

    /** @var Form_RaffleItem */
    protected $formRaffleItem;

    /** @var DoctrineObject */
    protected $doctrineObject;

    /** @var EntityManager */
    private $entityManager;

    /**
     * @param PayloadInterface $payload
     * @param Form_RaffleItem $formRaffleItem
     * @param DoctrineObject $doctrineObject
     * @param EntityManager $entityManager
     */
    public function __construct(
        PayloadInterface $payload,
        Form_RaffleItem $formRaffleItem,
        DoctrineObject $doctrineObject,
        EntityManager $entityManager
    )
    {
        $this->payload = $payload;
        $this->entityManager = $entityManager;
        $this->formRaffleItem = $formRaffleItem;
        $this->doctrineObject = $doctrineObject;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(array $input)
    {
        try {
            $this->formRaffleItem->fill($input);
            $isValid = $this->formRaffleItem->filter();
            if ($isValid) {
                $fields = $this->formRaffleItem->getValue();
                $raffleItem = new RaffleItem();
                $this->doctrineObject->hydrate($fields, $raffleItem);
                $this->entityManager->persist($raffleItem);
                $this->entityManager->flush();
                return $this->payload
                    ->withStatus(PayloadInterface::STATUS_CREATED)
                    ->withOutput([
                        'success' => true,
                        'id' => $raffleItem->getRaffleItemId(),
                    ]);
            } else {
                $hints = $this->formRaffleItem->getMessages();
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
