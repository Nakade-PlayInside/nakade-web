<?php
namespace User\Controller;

use User\Entity\Coupon;
use User\Services\MailService;
use User\Services\RepositoryService;
use User\Services\UserFormService;
use Zend\Form\Form;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Class ProfileController
 *
 * @package User\Controller
 */
class CouponController extends AbstractController
{
    /**
     * Showing the user's profile
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return new ViewModel(
            array(
               'coupons'    => $this->getUserMapper()->getCouponByUser($this->identity()),
           )
        );
    }

    /**
     * widget on dashboard
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function inviteAction()
    {
        /* @var $form \User\Form\BirthdayForm */
        $form = $this->getForm(UserFormService::INVITE_FRIEND_FORM);
        $coupon = new Coupon();
        $form->bindEntity($coupon);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();
            $form->setData($postData);

            if ($form->isValid()) {

                /* @var $coupon \User\Entity\Coupon */
                $coupon = $form->getData();
                $this->getUserMapper()->save($coupon);

                /* @var $mail \User\Mail\CouponMail */
                $mail = $this->getMailService()->getMail(MailService::COUPON_MAIL);
                $mail->setCoupon($coupon);
                $mail->sendMail($coupon);

                $this->flashMessenger()->addSuccessMessage('Your Invitation Is Send');
                $coupon = new Coupon();
                $form->bind($coupon);

            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }
        }

        return new ViewModel(
            array('form' => $form)
        );
    }

  /**
     * @return \User\Mapper\UserMapper
     */
    private function getUserMapper()
    {
        /* @var $repo \User\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::USER_MAPPER);
    }

}
