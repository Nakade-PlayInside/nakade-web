<?php
//module/Authentication/src/Authentication/Services/AuthServiceFactory.php
namespace League\Services;

use Nakade\Abstracts\AbstractService;
use League\Entity\League;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;


/**
 * Factory for the actual season for receiving
 * sorted league tables and schedules.
 *
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class MatchdayServiceFactory extends AbstractService
{

    /**
     * Actual Season Services for league tables and schedules.
     * Integration of optional translation feature (i18N)
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return ActualSeasonService
     * @throws RuntimeException
     *
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }

        //configuration
        $textDomain = isset($config['League']['text_domain']) ?
            $config['League']['text_domain'] : null;

        $this->setMapperFactory($services->get('League\Factory\MapperFactory'));

        //optional translator
        $translator = $services->get('translator');
        $this->setTranslator($translator, $textDomain);

        return $this;

    }

    /**
     *
     * @param int $id
     * @return Match
     */
    public function getMatch($id)
    {
        return $this->getMapper('match')->getMatchById($id);
    }
     /**
     * shows all open results in a season.
     * A link is provided for each match to enter a result.
     * @return mixed
     */
    public function getOpenMatches() {

        $season = $this->getMapper('season')->getActualSeason();
        if($season==null) {
            return null;
        }

        return $this->getMapper('match')
                    ->getAllOpenMatches($season->getId());
    }

     /**
      * Helper for formatting the SQL date
      *
      * @param DateTime $datetime
      * @return string
      */
     protected function formatDateTime($datetime)
     {
         if($datetime===null)
             return $datetime;

         $time = strtotime($datetime);
         return date('d.m.Y H:i' , $time);
     }




}


