<?php

namespace AppBundle\Listener;

use AppBundle\Entity\CountryUser;
use AppBundle\Entity\CrmUpdate;
use AppBundle\ObgIdGenerator\ObgIdGenerator;
use AppBundle\Service\SyncService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Session\Session;

class CRUDEventsListener
{

    private $obgIdGenerator;
    private $sync_service;

    /**
     * CRUDEventsListener constructor.
     *
     * Inject sync service.
     *
     * @param SyncService $syncService
     */
    public function __construct(SyncService $syncService)
    {
        $this->sync_service = $syncService;
        $this->obgIdGenerator = New ObgIdGenerator();
    }

    /**
     * Runs sync entity if database record has been updated.
     *
     * Uses doctrine events.
     * http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html#lifecycle-events
     *
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $entity = $args->getEntity();
        $entityType = $em->getClassMetadata(get_class($entity))->getTableName();

        $this->sync_service->logEntityToCrmUpdateTable($em, $args,'create');
        $this->sync_service->updateLastContactedField($em, $args);
        $this->sync_service->updateLastMetField($em, $entity, $entityType);
    }

    /**
     * Runs sync entity if database record has been created.
     *
     * Uses doctrine events.
     * http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html#lifecycle-events
     *
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $entity = $args->getEntity();
        $entityType = $em->getClassMetadata(get_class($entity))->getTableName();

        $this->sync_service->logEntityToCrmUpdateTable($em, $args,'update');
        $this->sync_service->updateLastContactedField($em, $args);
        $this->sync_service->updateLastMetField($em, $entity, $entityType);
    }

    /**
     * Runs sync entity if database record has been deleted.
     *
     * Uses doctrine events.
     * http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html#lifecycle-events
     *
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $this->sync_service->logEntityToCrmUpdateTable($em, $args,'delete');
    }

}
