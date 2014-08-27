<?php
namespace User\Controller;

use User\Entity\Coupon;
use User\Pagination\CouponPagination;
use User\Services\MailService;
use User\Services\RepositoryService;
use User\Services\UserFormService;
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
     * @return \Zend\View\Model\ViewModel
     */
    public function moderateAction()
    {
        $page = (int) $this->params()->fromRoute('id', 1);

        /* @var $entityManager \Doctrine\ORM\EntityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $pagination = new CouponPagination($entityManager);
        $offset = (CouponPagination::ITEMS_PER_PAGE * ($page -1));

        return new ViewModel(
            array(
                'paginator' =>   $pagination->getPagination($page),
                'coupons'    => $this->getUserMapper()->getCouponsByPages($offset),
            )
        );
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function inactivateAction()
    {
        //get param
        $couponId  = $this->params()->fromRoute('id', null);

        $coupon = $this->getUserMapper()->getCouponById($couponId);
        if (!is_null($coupon)) {
            $coupon->setExpiryDate(new \DateTime());
            $this->getUserMapper()->save($coupon);
            $this->flashMessenger()->addSuccessMessage('Coupon invalidated');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute('coupon', array('action' => 'moderate'));
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
