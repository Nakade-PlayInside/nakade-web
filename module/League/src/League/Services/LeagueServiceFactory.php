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
class LeagueServiceFactory extends AbstractService
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
     * get the actual season. If there is no actual season, the last season is
     * returned instead.
     *
     * @return mixed null|Season
     */
    public function getActualSeason()
    {

        $season = $this->getMapper('season')->getActualSeason();
        if(null===$season) {
            $season = $this->getMapper('season')->getLastSeason();
        }

        return $season;
    }

     /**
     * get the new season. The new season is the season following the actual or
     * last season.
     *
     * @return mixed null|Season
     */
    public function getNewSeason()
    {
        $season = $this->getActualSeason();
        if(null===$season) {
            return null;
        }

        $number = $season->getNumber()+1;
        return $this->getMapper('season')->getSeasonByNumber($number);
    }

    public function getNewLeague()
    {
       $season = $this->getNewSeason();
       if(null===$season) {
           return null;
       }

       $lno = $this->getMapper('league')
                   ->getLeagueNumberInSeason($season->getId()) + 1;


       $league = new League();
       $league->setSid($season->getId());
       $league->setNumber($lno);
       $title = sprintf('%s. Liga', $lno);
       $league->setTitle($title);

       return $league;
    }

    /**
     * adding an user
     *
     * @param Request $request
     * @param array $data
     */
    public function addLeague($data)
    {

         $league = new League();
         $league->exchangeArray($data);

         $this->getMapper('league')->save($league);

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


