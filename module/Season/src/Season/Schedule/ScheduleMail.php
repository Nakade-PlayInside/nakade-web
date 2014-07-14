<?php
namespace Season\Schedule;

use Season\Entity\Participant;
use Season\Entity\Season;
use Season\Services\MailService;
use Season\Services\RepositoryService;

/**
 * Class ScheduleMail
 *
 * @package Season\Schedule
 */
class ScheduleMail extends SeasonRepositoryBase
{
    private $mailService;
    private $scheduleMail;

    /**
     * @param RepositoryService $repositoryService
     * @param MailService       $mailService
     */
    public function __construct(RepositoryService $repositoryService, MailService $mailService)
    {
        parent::__construct($repositoryService);
        $this->mailService = $mailService;
    }

    /**
     * @param Season $season
     */
    public function sendMails(Season $season)
    {
        $players = $this->getSeasonMapper()->getPlayersInSeason($season->getId());
        $mail = $this->getScheduleMail();
        $mail->setSeason($season);

        /* @var $player \Season\Entity\Participant */
        foreach ($players as $player) {
            $matches = $this->getMatchScheduleByParticipant($player);
            $mail->setMatches($matches);
            $mail->sendMail($player->getUser());

        }
    }

    /**
     * @param Participant $participant
     *
     * @return array
     */
    private function getMatchScheduleByParticipant(Participant $participant)
    {
        $leagueId = $participant->getLeague()->getId();
        $userId = $participant->getUser()->getId();
        return $this->getLeagueMapper()->getPlayerScheduleByLeague($leagueId, $userId);
    }


    /**
     * @return \Season\Services\MailService
     */
    private function getMailService()
    {
        return $this->mailService;
    }

    /**
     * @return \Season\Mail\ScheduleMail
     */
    private function getScheduleMail()
    {
        if (is_null($this->scheduleMail)) {
            $this->scheduleMail = $this->getMailService()->getMail(MailService::SCHEDULE_MAIL);
        }
        return $this->scheduleMail ;
    }

}
