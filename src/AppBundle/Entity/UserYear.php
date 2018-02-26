<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserYear
 *
 * @ORM\Table(name="user_year", indexes={@ORM\Index(name="contact_id_idx", columns={"contact_id"}), @ORM\Index(name="country_id_idx", columns={"country_id"}), @ORM\Index(name="user_year_id", columns={"user_year_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserYearRepository")
 */
class UserYear
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_year_id", type="bigint", nullable=false)
     */
    private $userYearId;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_id", type="string", length=64, nullable=true)
     */
    private $contactId;

    /**
     * @var integer
     *
     * @ORM\Column(name="country_id", type="bigint", nullable=true)
     */
    private $countryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="project_year", type="bigint", nullable=true)
     */
    private $projectYear;

    /**
     * @var boolean
     *
     * @ORM\Column(name="read_only", type="boolean", nullable=true)
     */
    private $readOnly;

    /**
     * @var boolean
     *
     * @ORM\Column(name="removed", type="boolean", nullable=false)
     */
    private $removed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userYearId
     *
     * @param integer $userYearId
     * @return UserYear
     */
    public function setUserYearId($userYearId)
    {
        $this->userYearId = $userYearId;

        return $this;
    }

    /**
     * Get userYearId
     *
     * @return integer 
     */
    public function getUserYearId()
    {
        return $this->userYearId;
    }

    /**
     * Set contactId
     *
     * @param string $contactId
     * @return UserYear
     */
    public function setContactId($contactId)
    {
        $this->contactId = $contactId;

        return $this;
    }

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
     * Set countryId
     *
     * @param integer $countryId
     * @return UserYear
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Get countryId
     *
     * @return integer 
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * Set projectYear
     *
     * @param integer $projectYear
     * @return UserYear
     */
    public function setProjectYear($projectYear)
    {
        $this->projectYear = $projectYear;

        return $this;
    }

    /**
     * Get projectYear
     *
     * @return integer 
     */
    public function getProjectYear()
    {
        return $this->projectYear;
    }

    /**
     * Set readOnly
     *
     * @param boolean $readOnly
     * @return UserYear
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    /**
     * Get readOnly
     *
     * @return boolean 
     */
    public function getReadOnly()
    {
        return $this->readOnly;
    }

    /**
     * Set removed
     *
     * @param boolean $removed
     * @return UserYear
     */
    public function setRemoved($removed)
    {
        $this->removed = $removed;

        return $this;
    }

    /**
     * Get removed
     *
     * @return boolean 
     */
    public function getRemoved()
    {
        return $this->removed;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return UserYear
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return UserYear
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
