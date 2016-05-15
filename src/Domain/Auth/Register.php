<?php

namespace RaffleTools\Domain\Auth;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Equip\Adr\DomainInterface;
use Equip\Adr\PayloadInterface;
use RaffleTools\Domain\Auth\Form\Register as Register_Form;
use RaffleTools\Entity\User;

class Register implements DomainInterface
{
    /** @var PayloadInterface */
    private $payload;

    /** @var EntityManager */
    private $entityManager;

    /** @var DoctrineObject */
    protected $doctrineObject;

    /** @var Register_Form */
    protected $registerForm;

    /**
     * @param PayloadInterface $payload
     * @param EntityManager $entityManager
     * @param DoctrineObject $doctrineObject
     * @param Register_Form $registerForm
     */
    public function __construct(
        PayloadInterface $payload,
        EntityManager $entityManager,
        DoctrineObject $doctrineObject,
        Register_Form $registerForm
    )
    {
        $this->payload = $payload;
        $this->entityManager = $entityManager;
        $this->registerForm = $registerForm;
        $this->doctrineObject = $doctrineObject;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(array $input)
    {
        try {
            $this->registerForm->fill($input);
            $isValid = $this->registerForm->filter();
            if ($isValid) {
                $fields = $this->registerForm->getValue();
                $user = new User();
                $hash = password_hash($fields['password'], PASSWORD_DEFAULT, ['cost' => 14]);
                $user->setEmail($fields['email']);
                $user->setPasswordHash($hash);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return $this->payload
                    ->withStatus(PayloadInterface::STATUS_CREATED)
                    ->withOutput([
                        'success' => true,
                        'id' => $user->getUserId(),
                    ]);
            } else {
                $hints = $this->registerForm->getMessages();
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
