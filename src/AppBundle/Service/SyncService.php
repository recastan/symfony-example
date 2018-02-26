<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;

class SyncService
{
    protected $allowedEntityTypes;
    protected $session;

    /**
     * We should not sync all tables. Here the list of entities that we need.
     *
     * SyncService constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->allowedEntityTypes = [
            'companies',
            'contacts',
            'contacts_economic_updates_countries',
            'histories',
            'meeting_note_attendance',
            'meeting_note_content',
            'meeting_notes',
            'personal_assistants',
            'country_user'
        ];
    }

    /**
     * @return array
     */
    public function getAllowedEntityTypes()
    {
        return $this->allowedEntityTypes;
    }

    /**
     * Add new record to crm_update table.
     *
     * Adobe CRM will check this table when user tries to sync data between local database and server side database.
     *
     * @param $em
     * @param $listenerArgs
     * @param null $operationType
     */
    public function logEntityToCrmUpdateTable($em, $listenerArgs, $operationType = null) {
        $entity = $listenerArgs->getEntity();
        $class = get_class($entity);
        $entityType = $em->getClassMetadata($class)->getTableName();

        if (!in_array($entityType, $this->getAllowedEntityTypes()) || is_null($operationType)) {
            return;
        }

        //@todo: We should check what Adobe CRM will do if record with type 'delete' will be found.
        if (method_exists($entity, 'getRemoved') &&  $entity->getRemoved() && $operationType === 'update') {
            $operationType = 'delete';
        }

        if(!method_exists($entity, 'getRemoved') && $operationType === 'update') {
            $operationType = 'delete';
        }

        $crmUpdateArgs = [];
        $crmUpdateArgs['updateType'] = $operationType;
        $crmUpdateArgs['entityType'] = $entityType;
        $crmUpdateArgs['countryId'] = $this->session->get('project')['projectCountry'];
        $crmUpdateArgs['entityHashId'] = $entity->getHashId();

        $crmUpdateRepository = $em->getRepository('AppBundle:CrmUpdate');
        $crmUpdateRepository->newCrmUpdate($crmUpdateArgs);

    }

    /**
     * Update fields contain dynamic values of last contacted date.
     *
     * @param $em
     * @param $listenerArgs
     */
    public function updateLastContactedField($em, $listenerArgs) {
        $entity = $listenerArgs->getEntity();

        if( !method_exists($entity, 'getDateOfHistory') ) {
            return;
        }

        $companyId = $entity->getCompanyId();
        $contactId = $entity->getContactId();
        $contactLastContactedDate = $companyLastContactedDate = $entity->getDateOfHistory();

        if($companyId) {
            $company = $em->getRepository('AppBundle:Companies')->findOneBy(['companyId' => $companyId]);
        }

        if ($contactId) {
            $contact = $em->getRepository('AppBundle:Contacts')->findOneBy(['contactId' => $contactId]);
        }

        if($entity->getRemoved()) {
            $historiesRepo = $em->getRepository('AppBundle:Histories');
            if(isset($company)) {
                $companyLastContactedDate = $historiesRepo->getLastContactedDate('company', $entity->getCompanyId());
                $companyLastContactedDate = $companyLastContactedDate ? $companyLastContactedDate->getDateOfHistory() : null;
                $company->setLastContacted($companyLastContactedDate);
                $em->flush($company);
            }
            if(isset($contact)) {
                $contactLastContactedDate = $historiesRepo->getLastContactedDate('contact', $entity->getContactId());
                $contactLastContactedDate = $contactLastContactedDate ? $contactLastContactedDate->getDateOfHistory() : null;
                $contact->setLastContacted($contactLastContactedDate);
                $em->flush($contact);
            }
        }

        if(!$entity->getRemoved()) {
            if( isset($company) && ($companyLastContactedDate > $company->getLastContacted())) {
                $company->setLastContacted($companyLastContactedDate);
                $em->flush($company);
            }

            if( isset($contact)&& ($contactLastContactedDate > $contact->getLastContacted())) {
                $contact->setLastContacted($contactLastContactedDate);
                $em->flush($contact);
            }
        }

    }

    /**
     * Method for update dynamic values of last met date if meeting_note has been updated.
     *
     * @param $em
     * @param $entity
     * @param $entityType
     */
    public function updateLastMetField($em, $entity, $entityType) {
        if($entityType !== 'meeting_notes') {
            return;
        }

        $meetingDate = $entity->getMeetingDate();

        if((!$entity->getRemoved()) && ($meetingDate < time())) {

            $company = $em->getRepository('AppBundle:Companies')->findOneBy(['companyId' => $entity->getCompanyId()]);

            if ( $meetingDate > $company->getLastMet()) {
                $company->setLastMet($meetingDate);
            }

            if ($meetingDate < $company->getLastMet()) {
                $companyLatestMeetingDate = $em->getRepository('AppBundle:MeetingNotes')->findLatestMeetingDate($company->getCompanyId());
                if ($companyLatestMeetingDate != $company->getLastMet()) {
                    $company->setLastMet($companyLatestMeetingDate->getMeetingDate());
                    $meetingDate = $companyLatestMeetingDate->getMeetingDate();
                }
            }

            $em->flush($company);

            $contacts = $em->getRepository('AppBundle:Contacts')->findContactsByMeetingNote($entity->getMeetingNoteId());
            if(!empty($contacts)) {
                foreach ($contacts as $contact) {
                    if ( $meetingDate > $contact->getLastMet() ) {
                        $contact->setLastMet($meetingDate);
                        $em->flush($contact);
                    }
                }
            }
        }

        if($entity->getRemoved()){
            $company = $em->getRepository('AppBundle:Companies')->findOneBy(['companyId' => $entity->getCompanyId()]);
            $companyLatestMeetingDate = $em->getRepository('AppBundle:MeetingNotes')->findLatestMeetingDate($company->getCompanyId());
            if ($companyLatestMeetingDate) {
                $company->setLastMet(
                    $companyLatestMeetingDate->getMeetingDate()
                );
            }
            $em->flush($company);

            $contactsRepo = $em->getRepository('AppBundle:Contacts');
            $contacts = $contactsRepo->findContactsByMeetingNote($entity->getMeetingNoteId());

            foreach ($contacts as $contact) {
                $contactForUpdate = $contactsRepo->findLatestContactMeetingDate($contact->getContactId());
                if ($contactForUpdate) {
                    $contact->setLastMet($contactForUpdate->getMeetingDate());
                    $em->flush($contact);
                }
            }
        }
    }
}