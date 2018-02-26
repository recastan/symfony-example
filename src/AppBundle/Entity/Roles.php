<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Roles
 *
 * @ORM\Table(name="roles", indexes={@ORM\Index(name="role_id", columns={"role_id"})})
 * @ORM\Entity
 */
class Roles
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
     * @ORM\Column(name="role_id", type="bigint", nullable=true)
     */
    private $roleId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=2550, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="dashboard_weight", type="bigint", nullable=true)
     */
    private $dashboardWeight;

    /**
     * @var integer
     *
     * @ORM\Column(name="international", type="bigint", nullable=true)
     */
    private $international;

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
     * Set roleId
     *
     * @param integer $roleId
     * @return Roles
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get roleId
     *
     * @return integer 
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Roles
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
     * Set dashboardWeight
     *
     * @param integer $dashboardWeight
     * @return Roles
     */
    public function setDashboardWeight($dashboardWeight)
    {
        $this->dashboardWeight = $dashboardWeight;

        return $this;
    }

    /**
     * Get dashboardWeight
     *
     * @return integer 
     */
    public function getDashboardWeight()
    {
        return $this->dashboardWeight;
    }

    /**
     * Set international
     *
     * @param integer $international
     * @return Roles
     */
    public function setInternational($international)
    {
        $this->international = $international;

        return $this;
    }

    /**
     * Get international
     *
     * @return integer 
     */
    public function getInternational()
    {
        return $this->international;
    }

    /**
     * Set removed
     *
     * @param boolean $removed
     * @return Roles
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
     * @return Roles
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
     * @return Roles
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
