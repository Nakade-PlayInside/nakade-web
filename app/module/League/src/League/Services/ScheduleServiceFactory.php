<?php
//module/Authentication/src/Authentication/Services/AuthServiceFactory.php
namespace League\Services;

use League\Schedule\Schedule;
use Nakade\Abstracts\AbstractService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;


/**
 * Factory for the actual season for receiving
 * sorted league tables and schedules.
 * 
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class ScheduleServiceFactory extends AbstractService 
{
   
   
    /**
     * Actual Season Service for league tables and schedules.
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
     * get the new season. The new season is the season following the actual or
     * last season.
     * 
     * @return mixed null|Season
     */
    public function getNewSeason()
    {
        return $this->getMapper('season')->getNewSeason();
    }        
   
    public function getPlayers()
    {
       $season = $this->getNewSeason();
       if(null===$season) {
          return null;
       }
       
       return $this->getMapper('player')
                   ->getPlayersInSeason($season->getId());
       
    }
    
    public function addSchedule($data)
    {
       //ongoing season: no new schedule 
       if(null != $this->getMapper('season')->getActualSeason()) {
          return null;
       } 
     
       $season = $this->getNewSeason();
       if(null===$season) {
          return null;
       }
       
       $leagues = $this->getMapper('league')
                       ->getLeaguesInSeason($season->getId()); 
       if(null===$leagues) {
         return null;
       }
       
       $course = $data['course']==2? true:false;
       $date   = $data['startdate'] . " " . $data['starttime'];
       
       foreach($leagues as $league) {
            $player = $this->getMapper('player')
                           ->getAllPlayersInLeague($league->getId());
                 
            $schedule = new Schedule($date, $course);
            $pairing  = $schedule->makeSchedule($player);
            
            foreach($pairing as $match) {
                    $this->getMapper('match')->save($match);
            }
            
            
       } 
       
        
    }  
    
 
}


