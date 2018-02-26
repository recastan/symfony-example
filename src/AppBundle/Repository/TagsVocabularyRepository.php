<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TagsVocabularyRepository
 */
class TagsVocabularyRepository extends EntityRepository
{
    public function findAll() {
        $em = $this->_em;
        $qb = $em->createQueryBuilder()
            ->select('v.id', 'v.name' , 'et.entityName')
            ->from('AppBundle:TagsVocabulary', 'v')
            ->leftJoin('AppBundle:EntityTypes', 'et', 'WITH', 'v.entityTypeId = et.id')
            ->orderBy('v.name');
        return $qb->getQuery()->getArrayResult();
    }

    public function keyValuedListOfVocabularies() {
        $em = $this->_em;
        $qb = $em->createQueryBuilder()
            ->select('v.name')
            ->from('AppBundle:TagsVocabulary', 'v', 'v.id')
            ->orderBy('v.name');
        $results = $qb->getQuery()->getArrayResult();
        foreach ($results as $key=>$value) {
            $results[$key] = $value['name'];
        }
        return $results;
    }
}
