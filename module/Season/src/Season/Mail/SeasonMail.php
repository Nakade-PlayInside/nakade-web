<?php
namespace Season\Mail;

use Mail\NakadeMail;
use Mail\Services\MailMessageFactory;
use Season\Entity\Participant;
use Season\Entity\Season;
use Season\Schedule\DateHelper;
use \Zend\Mail\Transport\TransportInterface;

/**
 * Class SeasonMail
 *
 * @package Season\Mail
 */
abstract class SeasonMail extends NakadeMail
{
    protected $dateHelper;
    protected $season;
    protected $participant;

    /**
     * @param MailMessageFactory $mailService
     * @param TransportInterface $transport
     * @param DateHelper         $dateHelper
     */
    public function __construct(MailMessageFactory $mailService, TransportInterface $transport, DateHelper $dateHelper)
    {
        $this->mailService = $mailService;
        $this->transport = $transport;
        $this->dateHelper = $dateHelper;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {//todo: proof if this is needed
        $this->url = $url;
        return $this;
    }

    /**
     * @param Participant $participant
     */
    public function setParticipant(Participant $participant)
    {
        $this->participant = $participant;
        $this->setSeason($participant->getSeason());
    }

    /**
     * @return Participant
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * @param Season $season
     */
    public function setSeason(Season $season)
    {
        $this->season = $season;
    }

    /**
     * @return Season
     */
    public function getSeason()
    {
        return $this->season;
    }

    protected function makeReplacements(&$message)
    {
        $message = str_replace('%URL%', $this->getUrl(), $message);

        if (!is_null($this->season)) {

            $season = $this->getSeason();
            $seasonDates = $season->getAssociation()->getSeasonDates();
            $time = $season->getTime();
            $day = $seasonDates->getDay();
            $matchDay = $this->getDateHelper()->getDay($day);
            $period = $seasonDates->getCycle();
            $cycle = $this->getDateHelper()->getCycle($period);

            $message = str_replace('%ASSOCIATION%', $season->getAssociation()->getName(), $message);
            $message = str_replace('%NUMBER%', $season->getNumber(), $message);
            $message = str_replace('%DATE%', $season->getStartDate()->format('d.m.y'), $message);
            $message = str_replace('%BASE_TIME%', $time->getBaseTime(), $message);
            $message = str_replace('%ADDITIONAL_TIME%', $time->getAdditionalTime(), $message);
            $message = str_replace('%MOVES%', $time->getMoves(), $message);
            $message = str_replace('%BYOYOMI%', $time->getByoyomi()->getName(), $message);
            $message = str_replace('%KOMI%', $season->getKomi(), $message);
            $message = str_replace('%CYCLE%', $cycle, $message);
            $message = str_replace('%TIME%', $seasonDates->getTime()->format('H:i'), $message);
            $message = str_replace('%MATCH_DAY%', $matchDay, $message);

        }

        if (!is_null($this->participant)) {

            $link = sprintf('%s/playerConfirm?id=%d&confirm=%s',
                $this->getUrl(),
                $this->getParticipant()->getId(),
                $this->getParticipant()->getAcceptString()
            );

            $message = str_replace('%CONFIRM_LINK%', $link, $message);
        }
    }

    /**
     * @return DateHelper
     */
    public function getDateHelper()
    {
        return $this->dateHelper;
    }

}