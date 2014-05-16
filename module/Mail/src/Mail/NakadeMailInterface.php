<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 06.05.14
 * Time: 16:00
 */

namespace Mail;

use Mail\Services\MailMessageFactory;
use User\Entity\UserInterface;
use \Zend\Mail\Transport\TransportInterface;
use Mail\Services\MailSignatureService;

/**
 * Interface NakadeMailInterface
 *
 * @package Appointment\Mail
 */
interface NakadeMailInterface
{

    /**
     * @param UserInterface $user
     *
     * @return bool
     */
    public function sendMail(UserInterface $user);

    /**
     * @param MailMessageFactory $mailService
     *
     * @return $this;
     */
    public function setMailService(MailMessageFactory $mailService);

    /**
     * @return MailMessageFactory
     */
    public function getMailService();

    /**
     * @param TransportInterface $transport
     *
     * @return $this;
     */
    public function setTransport(TransportInterface $transport);

    /**
     * @return TransportInterface
     */
    public function getTransport();

    /**
     * @return string
     */
    public function getSubject();

    /**
     * @return string
     */
    public function getMailBody();

    /**
     * @return MailSignatureService
     */
    public function getSignature();

    /**
     * @param MailSignatureService $signatureService
     *
     * @return $this
     */
    public function setSignature(MailSignatureService $signatureService);
}