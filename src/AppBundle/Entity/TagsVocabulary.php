<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TagsVocabulary
 *
 * @ORM\Table(name="tags_vocabulary")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagsVocabularyRepository")
 */
class TagsVocabulary
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="entity_type_id", type="integer")
     */
    private $entityTypeId;

    /**
     * @var int
     *
     * @ORM\Column(name="country", type="integer")
     */
    private $country;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return TagsVocabulary
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
     * Set entityTypeId
     *
     * @param integer $entityTypeId
     *
     * @return TagsVocabulary
     */
    public function setEntityTypeId($entityTypeId)
    {
        $this->entityTypeId = $entityTypeId;

        return $this;
    }

    /**
     * Get entityTypeId
     *
     * @return int
     */
    public function getEntityTypeId()
    {
        return $this->entityTypeId;
    }

    public function __toString()
    {
        return (string)$this->getId();
    }

    /**
     * @return int
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param int $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
}

