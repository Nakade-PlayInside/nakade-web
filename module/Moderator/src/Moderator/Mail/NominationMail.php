<?php

namespace Moderator\Mail;

use Mail\NakadeMail;
use Mail\Services\MailMessageFactory;
use Moderator\Entity\LeagueManager;
use User\Entity\User;
use \Zend\Mail\Transport\TransportInterface;

/**
 * Class NominationMail
 *
 * @package Moderator\Mail
 */
abstract class NominationMail extends NakadeMail
{
    protected $user;
    protected $leagueManager;

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
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param LeagueManager $leagueManager
     */
    public function setLeagueManager(LeagueManager $leagueManager)
    {
        $this->leagueManager = $leagueManager;
    }

    /**
     * @return LeagueManager
     */
    public function getLeagueManager()
    {
        return $this->leagueManager;
    }

    protected function makeReplacements(&$message)
    {
        $message = str_replace('%URL%', $this->getUrl(), $message);

        if (!is_null($this->getUser())) {
            $message = str_replace('%NAME%', $this->getUser()->getFirstName(), $message);
        }
        if (!is_null($this->getLeagueManager())) {
            $message = str_replace('%NAME%', $this->getLeagueManager()->getManager()->getFirstName(), $message);
            $message = str_replace('%ASSOCIATION%', $this->getLeagueManager()->getAssociation()->getName(), $message);
            $message = str_replace('%OWNER%',
                $this->getLeagueManager()->getAssociation()->getOwner()->getShortName(),
                $message
            );
        }
    }
}