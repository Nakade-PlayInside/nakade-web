<?php

namespace Moderator\Mail;

use League\Standings\ResultInterface;
use Mail\NakadeMail;
use Mail\Services\MailMessageFactory;
use Moderator\Entity\StageInterface;
use Moderator\Entity\SupportRequest;
use Season\Entity\Match;
use \Zend\Mail\Transport\TransportInterface;

/**
 * Class SupportMail
 *
 * @package Moderator\Mail
 */
abstract class SupportMail extends NakadeMail implements StageInterface
{
    protected $ticket;

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
     * @param SupportRequest $ticket
     *
     * @return $this
     */
    public function setSupportRequest(SupportRequest $ticket)
    {
        $this->ticket = $ticket;
        return $this;
    }

    /**
     * @return SupportRequest
     */
    public function getSupportRequest()
    {
        return $this->ticket;
    }

    protected function makeReplacements(&$message)
    {
        $message = str_replace('%URL%', $this->getUrl(), $message);

        if (!is_null($this->getSupportRequest())) {

            $message = str_replace('%TICKET_NUMBER%', $this->getSupportRequest()->getId(), $message);

            $stage = $this->getStage($this->getSupportRequest()->getStage()->getId());
            $message = str_replace('%STAGE%', $stage, $message);

        }
    }

    protected function getStage($stage)
    {
        switch ($stage) {

            case self::STAGE_WAITING:
                $text = $this->translate('Waiting');
                break;

            case self::STAGE_REOPENED:
                $text = $this->translate('Reopened');
                break;

            case self::STAGE_IN_PROCESS:
                $text = $this->translate('In process');
                break;

            case self::STAGE_CANCELED:
                $text = $this->translate('Canceled');
                break;

            case self::STAGE_DONE:
                $text = $this->translate('Done');
                break;

            default:
                $text = $this->translate('New');
        }

        return $text;
    }

}