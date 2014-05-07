<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 06.05.14
 * Time: 16:00
 */

namespace Mail;

use User\Entity\User;
use Mail\Services\MailMessageFactory;
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
     * @param User $user
     *
     * @return bool
     */
    public function sendMail(User $user);

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