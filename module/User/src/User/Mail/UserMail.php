<?php
namespace User\Mail;

use Mail\NakadeMail;
use Mail\Services\MailMessageFactory;
use User\Entity\User;
use \Zend\Mail\Transport\TransportInterface;

/**
 * Class UserMail
 *
 * @package User\Mail
 */
abstract class UserMail extends NakadeMail
{
    protected $user;

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

    protected function makeReplacements(&$message)
    {
        $message = str_replace('%URL%', $this->getUrl(), $message);

        if (!is_null($this->getUser())) {

            $link = sprintf('%s/register/verify?email=%s&verify=%s',
                $this->getUrl(),
                $this->getUser()->getEmail(),
                $this->getUser()->getVerifyString()
            );

            $message = str_replace('%FIRST_NAME%', $this->getUser()->getFirstName(), $message);
            $message = str_replace('%USERNAME%', $this->getUser()->getUsername(), $message);
            $message = str_replace('%PASSWORD%', $this->getUser()->getPasswordPlain(), $message);
            $message = str_replace('%VERIFY_LINK%', $link, $message);
            $message = str_replace('%DUE_DATE%', $this->getUser()->getDue()->format('d.m.y H:i'), $message);
        }
    }

}