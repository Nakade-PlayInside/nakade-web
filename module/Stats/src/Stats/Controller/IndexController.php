<?php
namespace Stats\Controller;

use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 *
 * @package Stats\Controller
 */
class IndexController extends AbstractController
{
    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {

        return new ViewModel(
            array()
        );
    }


}
