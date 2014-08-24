<?php
namespace Arbitration\Controller;

use Arbitration\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Nakade\Pagination\ItemPagination;
use Zend\View\Model\ViewModel;

/**
 * Class ArbitrationController
 * for referees procedures only
 *
 * @package Arbitration\Controller
 */
class ArbitrationController extends AbstractController
{
    const HOME = 'arbitration';

    //todo: overview for referees about other referees or Mailing other referees or Voting

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {

        $page = (int) $this->params()->fromRoute('id', 1);

        $total = $this->getMapper()->getActiveRefereesByPages();
        $pagination = new ItemPagination($total);

        return new ViewModel(
            array(
                'referees' =>  $this->getMapper()->getActiveRefereesByPages($pagination->getOffset($page)),
                'paginator' => $pagination->getPagination($page),
            )
        );
    }

    /**
     * @return \Arbitration\Mapper\RefereeMapper
     */
    private function getMapper()
    {
        /* @var $repo \Arbitration\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::REFEREE_MAPPER);
    }

}
