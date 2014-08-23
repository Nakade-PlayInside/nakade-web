<?php
namespace Support\Controller;

use Support\Entity\Referee;
use Support\Services\FormService;
use Support\Services\MailService;
use Support\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Nakade\Pagination\ItemPagination;
use Zend\View\Model\ViewModel;

/**
 * Class ArbitrationController
 * for referees procedures only
 *
 * @package Support\Controller
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

        $total = $this->getMapper()->getRefereesByPages();
        $pagination = new ItemPagination($total);

        return new ViewModel(
            array(
                'referees' =>  $this->getMapper()->getRefereesByPages($pagination->getOffset($page)),
                'paginator' => $pagination->getPagination($page),
            )
        );
    }

    /**
     * @return \Support\Mapper\ManagerMapper
     */
    private function getMapper()
    {
        /* @var $repo \Support\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::MANAGER_MAPPER);
    }

}
