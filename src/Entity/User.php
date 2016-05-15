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
class User
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
    protected $userId;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $alias = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $name = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $firstName = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $lastName = '';

    /**
     * @var string
     * @Column(type="string", unique=true)
     */
    protected $email = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $passwordHash = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $image = '';

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return User
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set passwordHash
     *
     * @param string $passwordHash
     *
     * @return User
     */
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;

        return $this;
    }

    /**
     * Get passwordHash
     *
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return User
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
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

}