<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\EntityProjectTag;
use Symfony\Component\Security\Acl\Exception\Exception;

/**
 * TagsTermsRepository
 */
class TagsTermsRepository extends EntityRepository
{
    /**
     *
     * @return array
     */
    public function findTermsWithVocabularyName() {
        $em = $this->_em;
        $qb = $em->createQueryBuilder()
            ->select('term.id', 'term.name', 'term.tagsVocabularyId', 'vocabulary.name as vocabulary_name')
            ->from('AppBundle:TagsTerms', 'term')
            ->leftJoin('AppBundle:TagsVocabulary', 'vocabulary', 'WITH', 'term.tagsVocabularyId = vocabulary.id');
        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param $entityType
     * @return array
     */
    public function getListOfTermsForEntityType($entityType, $countryId) {
        $qb = $this->_em->createQueryBuilder()
            ->select('term.id', 'term.name', 'v.name as vocabulary_name')
            ->from('AppBundle:TagsTerms', 'term','term.id')
            ->leftJoin('AppBundle:TagsVocabulary', 'v', 'WITH', 'term.tagsVocabularyId = v.id')
            ->leftJoin('AppBundle:EntityTypes', 'et', 'WITH','v.entityTypeId = et.id')
            ->orderBy('v.name')
            ->addOrderBy('term.name')
            ->where('et.entityName = :entityType')
            ->andWhere('v.country = :countryId')
            ->setParameters([
                'entityType' => $entityType,
                'countryId' => $countryId
                ]);
        $results = $qb->getQuery()->getArrayResult();

        foreach ($results as $key => $value){
            $results[$key] = $value['name'] . ' (' . $value['vocabulary_name'] . ')';
        }

        return $results;
    }

    /**
     * Update tags callback for entity.
     *
     * @param $request
     * @param $entityType
     * @param null $formName
     * @param $currentTags
     * @param $entityId
     * @param $sessionProject
     * @return array
     */
    public function updateTagsForCurrentEntity($request, $entityType, $formName = null, $currentTags, $entityId, $sessionProject) {

        //@todo: Need to create service that will make try/catch and return answer from server. Here should be only entity related methods.
        $em = $this->_em;

        if ( $request->request->get('tags') ) {
            $tags = $request->request->get('tags');
        }
        else{
            $tags = isset($request->request->get($formName)['tags']) ?
                $request->request->get($formName)['tags']
                : null;
        }

        try{
            if ($tags) {
                foreach ($tags as $tag) {
                    if(!in_array($tag, $currentTags)) {
                        $entityTag  = new EntityProjectTag();
                        $entityTag->setEntityId($entityId);
                        $entityTag->setEntityType($entityType);
                        $entityTag->setProjectId($sessionProject['projectId']);
                        $entityTag->setTermId($tag);
                        $em->persist($entityTag);
                    }
                }

                foreach ($currentTags as $currentTag) {
                    if(!in_array($currentTag, $tags)) {
                        $tagForRemoving = $em->getRepository('AppBundle:EntityProjectTag')->findOneBy(array(
                                'termId' => $currentTag,
                                'entityId' => $entityId,
                                'projectId' => $sessionProject['projectId']
                            )
                        );
                        if ($tagForRemoving) {
                            $em->remove($tagForRemoving);
                        }
                    }
                }
            }

            if(!$tags && isset($currentTags)) {
                $tagsForRemoving = $em->getRepository('AppBundle:EntityProjectTag')->findBy(array(
                    'entityId' => $entityId,
                    'projectId' => $sessionProject['projectId']
                ));
                foreach ($tagsForRemoving as $extraTag){
                    $em->remove($extraTag);
                }
            }
        }

        catch (Exception $exception) {
            return [
                'type' => 'error',
                'message' => 'Something went wrong, please try again or contact OBG CRM Support Team'
            ];
        }

        return [
            'message' => ucfirst($entityType) . ' record has been successfully updated.',
            'type' => 'success',
            'backToPreviousPage' => true
        ];
    }

}
