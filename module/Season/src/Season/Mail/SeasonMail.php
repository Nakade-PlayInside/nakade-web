<?php
namespace Season\Mail;

use Mail\NakadeMail;
use Mail\Services\MailMessageFactory;
use Season\Entity\Season;
use \Zend\Mail\Transport\TransportInterface;

/**
 * Class SeasonMail
 *
 * @package Season\Mail
 */
abstract class SeasonMail extends NakadeMail
{
    protected $url = 'http://www.nakade.de';
    protected $season;

     /**
     * @param MailMessageFactory $mailService
     * @param TransportInterface $transport
     */
    public function __construct(MailMessageFactory $mailService, TransportInterface $transport)
    {
        $this->mailService = $mailService;
        $this->transport = $transport;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param Season $season
     *
     * @return $this
     */
    public function setMatch(Season $season)
    {
        $this->season = $season;
        return $this;
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

        if (!is_null($this->getSeason())) {

            $message = str_replace('%ASSOCIATION%', $this->getSeason()->getAssociation()->getName(), $message);
            $message = str_replace('%NUMBER%', $this->getSeason()->getNumber(), $message);
            $message = str_replace('%START_DATE%', $this->getSeason()->getStartDate()->format('d.m.y'), $message);
            $message = str_replace('%BASE_TIME%', $this->getSeason()->getTime()->getBaseTime(), $message);
            $message = str_replace('%ADDITIONAL_TIME%', $this->getSeason()->getTime()->getAdditionalTime(), $message);
            $message = str_replace('%MOVES%', $this->getSeason()->getTime()->getMoves(), $message);
            $message = str_replace('%BYOYOMI%', $this->getSeason()->getTime()->getByoyomi()->getName(), $message);
            $message = str_replace('%KOMI%', $this->getSeason()->getKomi(), $message);
            $message = str_replace('%TIEBREAK_1%', $this->getSeason()->getTieBreaker1()->getName(), $message);
            $message = str_replace('%TIEBREAK_2%', $this->getSeason()->getTieBreaker2()->getName(), $message);
            $message = str_replace('%TIEBREAK_3%', $this->getSeason()->getTieBreaker3()->getName(), $message);
        }
    }

}