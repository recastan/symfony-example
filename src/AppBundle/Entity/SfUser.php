<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sf_user",
 *     indexes={@ORM\Index(name="contact_id_idx", columns={"contact_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\sfUserRepository")
 */
class SfUser extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=2550, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=2550, nullable=true)
     */
    private $lastName;

    
    /**
     * @var string
     *
     * @ORM\Column(name="contact_id", type="string", length=64, nullable=true)
     */
    private $contactId;

    /**
     * @var int
     *
     * @ORM\Column(name="login_count", type="integer", nullable=true, options={"default": 0})
     */
    private $loginCount;


    /**
     * Get contactId
     *
     * @return string 
     */
    public function getContactId()
    {
        return $this->contactId;
    }
    
    /**
     * Set contactId
     *
     * @param string $contactId
     * @return SfUser
     */
    public function setContactId($contactId)
    {
        $this->contactId = $contactId;

        return $this;
    }

    /**
     * @return int
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }

    /**
     * @param int $loginCount
     */
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
}
