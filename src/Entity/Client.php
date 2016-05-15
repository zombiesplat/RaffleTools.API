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
class Client
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
    protected $clientId;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $name = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $ein = '';

    /**
     * @var string
     * @Column(type="string", unique=true)
     */
    protected $email = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $phone = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $contactName = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $address1 = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $address2 = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $city = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $state = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $postalCode = '';

    /**
     * @var string
     * @Column(type="string")
     */
    protected $country = '';

    /**
     * Get clientId
     *
     * @return integer
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Client
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
     * Set ein
     *
     * @param string $ein
     *
     * @return Client
     */
    public function setEin($ein)
    {
        $this->ein = $ein;

        return $this;
    }

    /**
     * Get ein
     *
     * @return string
     */
    public function getEin()
    {
        return $this->ein;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Client
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
     * Set phone
     *
     * @param string $phone
     *
     * @return Client
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set contactName
     *
     * @param string $contactName
     *
     * @return Client
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * Get contactName
     *
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set address1
     *
     * @param string $address1
     *
     * @return Client
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return Client
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Client
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Client
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return Client
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Client
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

}