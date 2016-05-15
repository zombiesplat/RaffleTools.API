<?php
namespace RaffleTools\Domain\Input\Validate;

use Aura\Filter\Rule;
use Doctrine\ORM\EntityManager;

class CheckUnique
{
    /** @var EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     *
     * @param object $subject The subject to be filtered.
     * @param string $field The subject field name.
     * @return bool True if the value was sanitized, false if not.
     */
    public function __invoke($subject, $field, $class)
    {
        $entity = $this->entityManager->getRepository($class)->findBy([$field => $subject->$field]);
        return empty($entity);
    }

}
