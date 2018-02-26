<?php

namespace AppBundle\Repository;

use AppBundle\AppBundle;
use AppBundle\Entity\Project;
use Doctrine\ORM;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Query\ResultSetMapping;

class UserYearRepository extends EntityRepository
{

    /**
     * @param array $params
     * @return array
     */
    public function getListOfUserProjectYears($params = array()) {
        if(empty($params['contactId'])) {
            return array();
        }
        $qb = $this->_em->createQueryBuilder()
            ->select('uy.projectYear', 'countries.name', 'uy.countryId')
            ->from('AppBundle:UserYear', 'uy')
            ->innerJoin('AppBundle:Countries', 'countries', 'WITH', "uy.countryId = countries.id")
            ->addGroupBy('countries.name')
            ->andWhere('uy.contactId = :contactId')
            ->setParameter('contactId', $params['contactId']);
        return $qb->getQuery()->setFirstResult(0)->setMaxResults(100)->getArrayResult();
    }

    /**
     * Return user available years for selected country
     * @param array $params
     * @return array
     */
    public function getUserAvailableYearsPerCountry($params = array()){
        if(empty($params['contactId']) || empty($params['countryId'])) {
            return array();
        }
        $qb = $this->_em->createQueryBuilder()
            ->select('uy.projectYear', 'py.id')
            ->from('AppBundle:UserYear', 'uy')
            ->innerJoin('AppBundle:ProjectYear', 'py', 'WITH', 'uy.projectYear = py.projectYear')
            ->where('uy.countryId = :countryId')
            ->andWhere('uy.contactId = :contactId')
            ->setParameters(array(
                'countryId' => $params['countryId'],
                'contactId' => $params['contactId']
            ));

        $result = $qb->getQuery()->getArrayResult();

        $list = array();
        foreach ($result as $key=>$value) {
            $list[$value['id']] = $value['projectYear'];
        }
        return $list;
    }

    /**
     * Return user available years for selected country
     * @param array $params
     * @return array
     */
    public function getYearsPerCountryWithoutProject($params = array()){
        if(empty($params['countryId'])) {
            return array();
        }
        $qb = $this->_em->createQueryBuilder()
            ->select('py.projectYear', 'py.id')
            ->from('AppBundle:ProjectYear', 'py')
            ->leftJoin('AppBundle:Project', 'p', 'WITH', 'py.projectYear = p.projectYear AND p.country = :countryId')
            ->andWhere('p.id is null')
            ->orderBy('py.projectYear')
            ->setParameters(array(
                'countryId' => $params['countryId'],
            ));

        $result = $qb->getQuery()->getArrayResult();
        $list = array();
        foreach ($result as $key=>$value) {
            $list[$value['id']] = $value['projectYear'];
        }
        return $list;
    }
}