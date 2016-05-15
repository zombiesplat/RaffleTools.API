<?php
namespace RaffleTools\Entity\Traits;

trait SoftDelete
{
    /**
     * @var \DateTime
     * @Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return $this
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

}