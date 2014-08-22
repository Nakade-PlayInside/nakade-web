<?php
namespace Support\Controller;

use Support\Entity\StageInterface;
use Support\Entity\SupportRequest;
use Support\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;

/**
 * Class ManagerController
 *
 * @package Support\Controller
 */
class DefaultController extends AbstractController implements StageInterface, SupportTypeInterface
{


    /**
     * @return \Support\Mapper\ManagerMapper
     */
    protected function getMapper()
    {
        /* @var $repo \Support\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::MANAGER_MAPPER);
    }

    /**
     * @return \Support\Mapper\TicketMapper
     */
    protected function getTicketMapper()
    {
        /* @var $repo \Support\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::TICKET_MAPPER);
    }

    /**
     * @param int $stageId
     *
     * @return \Support\Entity\SupportStage
     */
    protected function getStageById($stageId)
    {
        return $this->getMapper()->getEntityManager()->getReference('Support\Entity\SupportStage', intval($stageId));
    }

    /**
     * @param int $typeId
     *
     * @return \Support\Entity\SupportType
     */
    protected function getTypeById($typeId)
    {
        return $this->getMapper()->getEntityManager()->getReference('Support\Entity\SupportType', intval($typeId));
    }

    /**
     * @param int $userId
     *
     * @return \User\Entity\User
     */
    protected function getUserById($userId)
    {
        return $this->getMapper()->getEntityManager()->getReference('User\Entity\User', intval($userId));
    }

    /**
     * @param int $ticketId
     * @param int $state
     *
     * @return null|SupportRequest
     */
    protected function setTicketState($ticketId, $state)
    {
        $ticket = $this->getTicketMapper()->getTicketById($ticketId);

        if (!is_null($ticket)) {
            $stage = $this->getStageById($state);
            $ticket->setStage($stage);
            $this->getMapper()->save($ticket);
        }
        return $ticket;
    }

    /**
     * @param SupportRequest $ticket
     * @param string $mailType
     */
    protected function sendMail(SupportRequest $ticket, $mailType)
    {
        $mail = $this->getMailService()->getMail($mailType);
        $mail->setSupportRequest($ticket);
        $mail->sendMail($ticket->getCreator());
    }

}
