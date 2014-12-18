<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Services;

use League\Entity\Player;
use Season\Entity\League;
use Stats\Calculation\AchievementStatsFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;


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
    private $translator;

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
        $this->factory = new AchievementStatsFactory();

        return $this;
    }

    public function evalAward($userId, $leagueId)
    {

        $league = $this->getLeague($leagueId);
        $matches = $this->getMatchesByLeague($league);
        $table = $this->getTable($matches);
        $player = $this->getPlayer($table, $userId);

    }

    public function getName(Player $player)
    {
        return $player->getUser()->getCertificateName();
    }

    public function getTournamentInfo(League $league)
    {
        $info = sprintf('%s. %s %s %s',
            $league->getSeason()->getNumber(),
            $league->getSeason()->getAssociation()->getName(),
            $this->getTournamentType($league),
            $league->getSeason()->getStartDate()->format('Y'),
            $league->getNumber()
        );

        if ($this->hasDetails($league)) {
            $info .= ' - ' . $this->getLeagueDetails($league);
        }
        return $info;
    }

    private function getTournamentType(League $league)
    {
        $type = $league->getSeason()->getAssociation()->getType()->getId();
        $info = $this->translate('Tournament');

        if ($type==1) {
            $info = $this->translate('League');
        }

        return $info;
    }

    private function getLeagueDetails(League $league)
    {
        $details = '';
        if ($this->hasDetails($league)) {
            sprintf('%s. %s',
                $league->getNumber(),
                $this->translate('Division')
            );
        }

        return $details;
    }

    private function hasDetails(League $league)
    {
        return $league->getSeason()->getAssociation()->getType()->getId() == 1;
    }

    /**
     * @param int $leagueId
     *
     * @return League
     */
    private function getLeague($leagueId)
    {
        return $this->getLeagueMapper()->getLeagueById($leagueId);
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
     * @param League $league
     *
     * @return array
     */
    private function getMatchesByLeague(League $league)
    {
        return $this->getMapper()->getMatchesByTournament($league->getId());
    }

    /**
     * @param array $matches
     *
     * @return array
     */
    private function getTable(array $matches)
    {
        return $this->getPlayerTableService()->getTable($matches);
    }

    /**
     * @param array $table
     * @param int $userId
     *
     * @return \League\Entity\Player|null
     */
    private function getPlayer(array $table, $userId)
    {
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
     * @return AchievementStatsFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }



    /**
     * @param Translator $translator
     *
     * @param string     $textDomain
     *
     * @return Translator|TranslatorAwareInterface
     */
    public function setTranslator(Translator $translator = null, $textDomain = null)
    {
        if (isset($translator)) {
            $this->translator=$translator;
        }

        if (isset($textDomain)) {
            $this->textDomain=$textDomain;
        }
        return $translator;
    }

    /**
     * Returns translator used in object
     *
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Checks if the object has a translator
     *
     * @return bool
     */
    public function hasTranslator()
    {
        return isset($this->translator);
    }


    /**
     * Helper for i18N. If a translator is set to the controller, the
     * message is translated.
     *
     * @param string $message
     *
     * @return string
     */
    public function translate($message)
    {

        $translator = $this->getTranslator();
        if (is_null($translator)) {
            return $message;
        }

        return $translator->translate(
            $message,
            $this->getTranslatorTextDomain()
        );

    }


}