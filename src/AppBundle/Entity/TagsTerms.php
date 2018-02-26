<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TagsTerms
 *
 * @ORM\Table(name="tags_terms")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagsTermsRepository")
 */
class TagsTerms
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="tags_vocabulary_id", type="integer")
     */
    private $tagsVocabularyId;


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
     * @return TagsTerms
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
     * Set tagsVocabularyId
     *
     * @param integer $tagsVocabularyId
     *
     * @return TagsTerms
     */
    public function setTagsVocabularyId($tagsVocabularyId)
    {
        $this->tagsVocabularyId = $tagsVocabularyId;

        return $this;
    }

    /**
     * Get tagsVocabularyId
     *
     * @return int
     */
    public function getTagsVocabularyId()
    {
        return $this->tagsVocabularyId;
    }
}

