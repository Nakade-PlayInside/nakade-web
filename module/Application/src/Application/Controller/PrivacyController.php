<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class PrivacyController
 *
 * @package Application\Controller
 */
class PrivacyController extends AbstractActionController
{

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }

}