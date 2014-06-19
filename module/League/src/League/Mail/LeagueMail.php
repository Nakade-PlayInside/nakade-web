<?php
/**
 * User: Holger Maerz
 * Date: 16.04.14
 * Time: 11:47
 */

namespace League\Mail;

use League\Standings\ResultInterface;
use Mail\NakadeMail;
use Mail\Services\MailMessageFactory;
use Season\Entity\Match;
use \Zend\Mail\Transport\TransportInterface;

/**
 * Class AppointmentMail
 *
 * @package Appointment\Mail
 */
abstract class LeagueMail extends NakadeMail implements ResultInterface
{
    protected $url = 'http://www.nakade.de';
    protected $match;

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
     * @param Match $match
     *
     * @return $this
     */
    public function setMatch(Match $match)
    {
        $this->match = $match;
        return $this;
    }

    /**
     * @return Match
     */
    public function getMatch()
    {
        return $this->match;
    }

    protected function makeReplacements(&$message)
    {
        $message = str_replace('%URL%', $this->getUrl(), $message);

        if (!is_null($this->getMatch())) {

            $message = str_replace('%MATCH_INFO%', $this->getMatch()->getMatchInfo(), $message);
            $message = str_replace('%NEW_DATE%', $this->getMatch()->getDate()->format('d.m.y H:i'), $message);
            $message = str_replace('%OLD_DATE%', $this->getMatch()->getMatchDay()->getDate()->format('d.m.y H:i'), $message);
            $message = str_replace('%RESULT%', $this->getResult($this->getMatch()), $message);
        }
    }

    protected function getResult(Match $match)
    {
        if (!$match->hasResult()) {
            return $this->translate('No result was found.');
        }

        switch ($match->getResult()->getResultType()->getId()) {

            case self::DRAW:
                $info = $this->translate("Result is a draw.");
                break;

            case self::SUSPENDED:
                $info = $this->translate("Your match was suspended.");
                break;

            case self::RESIGNATION:
                $info = $this->translate("%COLOR% win by resignation.");
                break;

            case self::FORFEIT:
                $info = $this->translate("%COLOR% win by forfeit.");
                break;

            case self::ONTIME:
                $info = $this->translate("%COLOR% win on time.");
                break;

            case self::BYPOINTS:
                $info = $this->translate("%COLOR% win by %POINTS% points.");
                break;

            default:
                throw new \RuntimeException(
                    sprintf('An unknown result type was provided.')
                );
        }

        $info = str_replace('%POINTS%', $this->getMatch()->getResult()->getPoints(), $info);
        $info = str_replace('%COLOR%', $this->getColor($this->getMatch()), $info);

        return $info;

    }

    protected function getColor(Match $match)
    {
        if (is_null($match->getResult()->getWinner())) {
            return $this->translate('Nobody');
        }

        $color =
            $match->getResult()->getWinner() == $match->getBlack()? $this->translate('Black'):$this->translate('White');
        return sprintf('%s (%s)', $color, $match->getResult()->getWinner()->getShortName());
    }


}