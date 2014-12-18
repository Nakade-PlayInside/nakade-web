<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Services;

use Stats\Calculation\CertificateFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CertificateService
 * returns name, place and tournament information
 *
 * @package Stats\Services
 */
class CertificateService extends AbstractStatsService
{
    private $standings;
    private $factory;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Zend\ServiceManager\FactoryInterface
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $this->repository = $services->get('Stats\Services\RepositoryService');
        $this->standings  = $services->get('Nakade\Services\PlayersTableService');


        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['Stats']['text_domain']) ?
            $config['Stats']['text_domain'] : null;

        $translator = $services->get('translator');
        $this->factory = new CertificateFactory($translator, $textDomain);

        return $this;

    }

    public function getCertificate($userId, $leagueId)
    {

        $matches = $this->getMapper()->getMatchesByTournament($leagueId);

        //no certificates for ongoing tournaments
        if (empty($matches) || $this->isOngoing($matches)) {
            return null;
        }

        $league = $this->getLeagueMapper()->getLeagueById($leagueId);
        $player = $this->getPlayer($matches, $userId);

        return $this->getFactory()->getCertificate($league, $player);
    }

    /**
     * @param array $matches
     *
     * @return bool
     */
    public function isOngoing(array $matches)
    {
        /* @var $match \Season\Entity\Match */
        foreach ($matches as $match) {

            if (!$match->hasResult()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $matches
     * @param int $userId
     *
     * @return \League\Entity\Player|null
     */
    private function getPlayer(array $matches, $userId)
    {
        $table = $this->getPlayerTableService()->getTable($matches);

        /* @var  $player \League\Entity\Player */
        foreach ($table as $player) {

            if ($player->getUser()->getId() == $userId) {
                return $player;
            }
        }
        return null;
    }

    /**
     * @return \Nakade\Services\PlayersTableService
     */
    public function  getPlayerTableService()
    {
        return $this->standings;
    }

    /**
     * @return CertificateFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

}