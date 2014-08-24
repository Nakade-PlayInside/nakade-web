<?php

namespace Application\Controller;


use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 *
 * @package Application\Controller
 */
class IndexController extends AbstractController
{

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $matchDay  = $this->params('id');
        $page  = intval($this->params('page', 1));

        $blogWidget  = $this->forward()
                            ->dispatch('/Blog/Controller/Blog', array ('action' => 'index', 'page' => $page));

        $tableWidget = $this->forward()
                            ->dispatch('/League/Controller/Table');

        $resultWidget = $this->forward()->dispatch('/League/Controller/Result',
            array('action' => 'actualResults', 'id' => $matchDay)
        );

        $rulesWidget = $this->forward()->dispatch('/Season/Controller/Season',
            array('action' => 'showRules')
        );

        $registerWidget = $this->forward()->dispatch('/User/Controller/Registration',
            array('action' => 'show')
        );

        $page = new ViewModel(array( 'voter' => $this->getService()));

        $page->addChild($blogWidget, 'blogWidget');
        $page->addChild($tableWidget, 'tableWidget');
        $page->addChild($resultWidget, 'resultWidget');
        $page->addChild($rulesWidget, 'rulesWidget');

        //open beta until end of year
        if (date('c') < date('c', strtotime('12/31/2014'))) {
            $page->addChild($registerWidget, 'registerWidget');
        }


        return $page;

    }

}
