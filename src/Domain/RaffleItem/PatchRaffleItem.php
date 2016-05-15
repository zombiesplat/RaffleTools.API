<?php
namespace RaffleTools\Domain\RaffleItem;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Equip\Adr\DomainInterface;
use Equip\Adr\PayloadInterface;
use RaffleTools\Entity\RaffleItem;
use RaffleTools\Domain\RaffleItem\Form\Patch as Form_RaffleItem;

class PatchRaffleItem implements DomainInterface
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
            $raffleItem = $this->entityManager->getRepository(RaffleItem::class)->find($input['id']);
            if (empty($raffleItem)) {
                return $this->payload
                    ->withStatus(PayloadInterface::STATUS_NOT_FOUND)
                    ->withOutput([
                        'success' => false,
                    ]);
            }
            foreach ($input as $field => $value) { //array keys input?
                $this->formRaffleItem->addField($field);
            }
            $this->formRaffleItem->fill($input);
            if (empty($this->formRaffleItem->getInputNames())) {
                return $this->payload
                    ->withStatus(PayloadInterface::STATUS_NOT_MODIFIED)
                    ->withOutput([
                        'success' => true,
                    ]);
            }
            $isValid = $this->formRaffleItem->filter();
            if ($isValid) {
                $fields = $this->formRaffleItem->getValue();
                $this->doctrineObject->hydrate($fields, $raffleItem);
                $this->entityManager->persist($raffleItem);
                $this->entityManager->flush();
                return $this->payload
                    ->withStatus(PayloadInterface::STATUS_ACCEPTED)
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
