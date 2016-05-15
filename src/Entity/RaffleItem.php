<?php
namespace RaffleTools\Entity;

use Doctrine\ORM\Mapping as ORM;
use RaffleTools\Entity\Traits\LifeCycleAware;
use RaffleTools\Entity\Traits\SoftDelete;

/**
 *
 * @Entity
 * @HasLifecycleCallbacks
 **/
class RaffleItem
{
    use LifeCycleAware;
    use SoftDelete;

    /**
     * @var \Ramsey\Uuid\Uuid
     *
     * @Id
     * @Column(type="uuid")
     * @GeneratedValue(strategy="CUSTOM")
     * @CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected $raffleItemId;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $image;


    /**
     * Get raffleItemId
     *
     * @return int
     */
    public function getRaffleItemId()
    {
        return $this->raffleItemId;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param $image
     *
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

}