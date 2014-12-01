<?php
namespace Stats\Controller;

use Nakade\Abstracts\AbstractController;
use Stats\Calculation\MatchStatsFactory;
use Stats\Services\RepositoryService;
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

        $userId = $this->identity()->getId();

        /* @var $mapper \Stats\Mapper\StatsMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::STATS_MAPPER);

        $matches = $mapper->getMatchStatsByUser($userId);
        $factory = new MatchStatsFactory($matches, $userId);
        $stats = $factory->getMatchStats();

        return new ViewModel(
            array('stats' => $stats)
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function achievementAction()
    {

        $userId = $this->identity()->getId();

        /* @var $mapper \Stats\Mapper\StatsMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::STATS_MAPPER);

        $tournaments = $mapper->getTournamentsByUser($userId);



        $matches = $mapper->getMatchesByTournament(1);
        $table = $this->getService()->getTable($matches); //$pos->getPosition();

        $pos = 1;
        foreach($table as $player) {

            if($player->getUser()->getId()==$userId) {
                break;
            }
            $pos++;
        }
        var_dump($pos);
        //var_dump($tournaments);die;
       // $factory = new MatchStatsFactory($matches, $userId);
       // $stats = $factory->getMatchStats();

        return new ViewModel(
            array(
                'tournaments' => $tournaments,
                'position' => $pos

            )
        );
    }


}
