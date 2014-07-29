<?php
namespace User\Mail;

use Mail\NakadeMail;
use Mail\Services\MailMessageFactory;
use User\Entity\Coupon;
use Zend\Mail\Transport\TransportInterface;

/**
 * Class CouponMail
 *
 * @package User\Mail
 */
class CouponMail extends NakadeMail
{

    private $url = 'http://www.nakade.de';
    private $coupon;

    /**
     * @param MailMessageFactory $mailService
     * @param TransportInterface $transport
     */
    public function __construct(MailMessageFactory $mailService, TransportInterface $transport)
    {
        $this->mailService = $mailService;
        $this->transport = $transport;
        $this->url = 'http://'. $_SERVER['HTTP_HOST'];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate("Dear Go Friend") . ', ' .
            PHP_EOL . PHP_EOL .
            $this->translate('You are invited to sign up for closed beta at %URL%.') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate("Your Credentials") . ': ' . PHP_EOL . PHP_EOL .
            $this->translate("username") . ': ' . '%USERNAME%' . PHP_EOL .
            $this->translate("password") . ': ' . '%PASSWORD%' . PHP_EOL .
            PHP_EOL . PHP_EOL .
            $this->translate("For security reasons, change the generated password as soon as possible.") . ' ' .
            $this->translate("Therefore, logIn and go to your profile.") . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate('In case of a problem, please contact us.') . PHP_EOL . PHP_EOL .
            $this->getSignature()->getSignatureText();

        $this->makeReplacements($message);

        return $message;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        $subject = $this->translate('Invitation By %NAME%');
        $this->makeReplacements($subject);

        return $subject;
    }

    /**
     * @param Coupon $coupon
     */
    public function setCoupon(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    /**
     * @return Coupon
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    private function makeReplacements(&$message)
    {
        $message = str_replace('%URL%', $this->getUrl(), $message);

        if (!is_null($this->getCoupon())) {

            $link = sprintf('%s/register?email=%s&code=%s',
                $this->getUrl(),
                $this->getCoupon()->getEmail(),
                $this->getCoupon()->getCode()
            );

            $message = str_replace('%NAME%', $this->getCoupon()->getCreatedBy()->getName(), $message);
            $message = str_replace('%USERNAME%', $this->getUser()->getUsername(), $message);
            $message = str_replace('%PASSWORD%', $this->getUser()->getPasswordPlain(), $message);
            $message = str_replace('%VERIFY_LINK%', $link, $message);
            $message = str_replace('%DUE_DATE%', $this->getUser()->getDue()->format('d.m.y H:i'), $message);
        }
    }



}