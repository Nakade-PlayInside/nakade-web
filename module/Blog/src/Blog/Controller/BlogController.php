<?php
namespace Blog\Controller;

use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractController
{

    /**
     * @return array|ViewModel
     */
   public function indexAction()
   {
       $page  = (int) $this->params()->fromRoute('page', 1);

       return new ViewModel(array(
             'post' =>  $this->getService()->getCarouselPaging($page),
          )
       );
   }

}