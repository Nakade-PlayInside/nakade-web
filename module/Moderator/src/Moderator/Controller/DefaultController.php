<?php
namespace Moderator\Controller;

use Moderator\Entity\StageInterface;
use Moderator\Entity\SupportRequest;
use Moderator\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;

/**
 * Class ManagerController
 *
 * @package Moderator\Controller
 */
class DefaultController extends AbstractController implements StageInterface, SupportTypeInterface
{


    /**
     * @return \Moderator\Mapper\ManagerMapper
     */
    protected function getMapper()
    {
        /* @var $repo \Moderator\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::MANAGER_MAPPER);
    }

    /**
     * @return \Moderator\Mapper\TicketMapper
     */
    protected function getTicketMapper()
    {
        /* @var $repo \Moderator\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::TICKET_MAPPER);
    }

    /**
     * @param int $stageId
     *
     * @return \Moderator\Entity\SupportStage
     */
    protected function getStageById($stageId)
    {
        return $this->getMapper()->getEntityManager()->getReference('Moderator\Entity\SupportStage', intval($stageId));
    }

    /**
     * @param int $typeId
     *
     * @return \Moderator\Entity\SupportType
     */
    protected function getTypeById($typeId)
    {
        return $this->getMapper()->getEntityManager()->getReference('Moderator\Entity\SupportType', intval($typeId));
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
