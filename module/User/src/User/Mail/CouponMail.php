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
    private $coupon;

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
    public function getMailBody()
    {
        $message =
            $this->translate("Dear Go Friend") . ', ' .
            PHP_EOL . PHP_EOL .
            $this->translate('You are invited to sign up at %URL%.') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate('Nakade is a platform for organizing leagues for the game of Go.') . PHP_EOL .
            $this->translate('For our closed beta, we are looking for players who love to compete on even terms.') .
            ' ' .
            $this->translate('Matches are frequently every 3 weeks, so this is best suited for the busy ones.') .
            PHP_EOL . PHP_EOL .
            $this->translate('If you love the game, you will love us!') . ' ' .
            PHP_EOL . PHP_EOL .
            "%NOTICE%" .
            PHP_EOL . PHP_EOL .
            $this->translate("To start sign up, please click on the link below") . ': ' .
            PHP_EOL . PHP_EOL . '%REGISTER_LINK%' . PHP_EOL . PHP_EOL .
            $this->translate("or you go to %URL% and enter the provided link.") . PHP_EOL . PHP_EOL .
            $this->translate("To sign up to our closed beta the provided coupon code is neccessary.") . PHP_EOL .
            $this->translate("The coupon will expire on %EXPIRE_DATE%.") . PHP_EOL .
            $this->translate("For more information just visit our website.") .
            PHP_EOL . PHP_EOL .
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

    protected function makeReplacements(&$message)
    {
        $message = str_replace('%URL%', $this->getUrl(), $message);

        if (!is_null($this->getCoupon())) {

            $link = sprintf('%s/register?email=%s&code=%s',
                $this->getUrl(),
                $this->getCoupon()->getEmail(),
                $this->getCoupon()->getCode()
            );

            $message = str_replace('%NAME%', $this->getCoupon()->getCreatedBy()->getName(), $message);
            $message = str_replace('%EXPIRE_DATE%', $this->getCoupon()->getExpiryDate()->format('m.d.y H:i'), $message);
            $message = str_replace('%REGISTER_LINK%', $link, $message);
            $message = str_replace('%CODE%', $this->getCoupon()->getCode(), $message);
            $message = str_replace('%NOTICE%', $this->getNotice(), $message);

        }
    }

    private function getNotice()
    {
        $userMessage = $this->getCoupon()->getMessage();
        $notice ='';
        if(!empty($userMessage)) {
            $notice =
                $this->translate("%FIRST_NAME% wrote") . ': ' . PHP_EOL . PHP_EOL .
                '"' . $userMessage . '"' .
                PHP_EOL . PHP_EOL . PHP_EOL;

            $notice = str_replace('%FIRST_NAME%', $this->getCoupon()->getCreatedBy()->getFirstName(), $notice);
        }


        return $notice;

    }



}