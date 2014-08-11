<?php
namespace Moderator\Controller;

use Moderator\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * Class ManagerController
 *
 * @package Moderator\Controller
 */
class ManagerController extends AbstractController
{

   /**
    * @return array|ViewModel
    */
    public function indexAction()
    {
        return new ViewModel(
            array(
              //  'paginator' => $paginator,
                'managers' =>  $this->getMapper()->getLeagueManager(),
            )
        );

    }

    /**
     * @return \Moderator\Mapper\ManagerMapper
     */
    private function getMapper()
    {
        /* @var $repo \Moderator\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::MANAGER_MAPPER);
    }

}
