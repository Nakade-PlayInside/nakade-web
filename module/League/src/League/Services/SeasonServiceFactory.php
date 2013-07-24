<?php
//module/Authentication/src/Authentication/Services/AuthServiceFactory.php
namespace League\Services;

use Nakade\Abstracts\AbstractService;
use League\Entity\Season;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;


/**
 * Factory for the actual season for receiving
 * sorted league tables and schedules.
 * 
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class SeasonServiceFactory extends AbstractService 
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
     * Get number of open matches in a season 
     * @return string
     */
    public function getActualStatus()
    {
        $matches = $this->translate('no matches');
        $open    = $this->translate('open matches');
        $first   = $this->translate('first match');
        $last    = $this->translate('last match');
        $status  = $this->translate('status');
        
        $season = $this->getActualSeason();
        if(null===$season)
            return null;
        
        $state= array();
        
        $state[$matches]  = $this->getMapper('match')
                                 ->getNumberOfMatches( $season->getId() );
        
        $state[$open]     = $this->getMapper('match')
                                 ->getOpenMatches( $season->getId() );
        
        $firstMatch = $this->getMapper('match')
                           ->getFirstMatchDate( $season->getId() );
        
        $state[$first]    = $this->formatDateTime($firstMatch);
       
        $lastMatch  = $this->getMapper('match')
                           ->getLastMatchDate( $season->getId() );
        
        $state[$last]    = $this->formatDateTime($lastMatch);
        
        $state[$status] = $this->translate('unknown');
        if($state[$matches] == $state[$open]) {
            $state[$status] = $this->translate('open'); 
        }    
        elseif( $state[$open] > 0 && $state[$open] < $state[$matches] ) {
            $state[$status] = $this->translate('ongoing');
        }    
        elseif($state[$open] == 0 ) {
            $state[$status] = $this->translate('ended');
        }    
        
        
        return $state;
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
    
    public function getNewSeasonStatus()
    {
        $league   = $this->translate('leagues');
        $player   = $this->translate('participants');
        $empty    = $this->translate('empty leagues');
        $schedule = $this->translate('match schedule');
        $status   = $this->translate('status');
        $yes      = $this->translate('yes');
        $no       = $this->translate('no');
        
        
        $season = $this->getNewSeason();
        if(null===$season)
            return null;
        
        $state= array();
        
        $state[$league]     = $this->getMapper('league')
                                   ->getLeagueNumberInSeason($season->getId() );
        $state[$player]     = $this->getMapper('player')
                                   ->getPlayerNumberInSeason($season->getId() );
        
        
        $emptyLeagues = $this->getMapper('league')
                             ->getLeaguesWithPlayers($season->getId() );
       
        $state[$empty]     = $emptyLeagues>0?$yes:$no;
        
        $matches = $this->getMapper('match')
                        ->getNumberOfMatches($season->getId() );
        
        
        $state[$schedule]   = $matches==0?$no:$yes;
        
        $addLeague = sprintf('<a href="%s">%s</a>',
                    'league/add',
                    $this->translate("add league")
                );
        
        $addPlayer = sprintf('<a href="%s">%s</a>',
                    '/player/add',
                    $this->translate("add player")
                );
        
        $editLeague = sprintf('<a href="%s">%s</a>',
                    '/editLeague',
                    $this->translate("edit league")
                );
        
        $state[$status]     = $addLeague;
        if($state[$league]==0) {
            $state[$status] = $addLeague;
        }
        elseif($state[$league]>0) {
            $state[$status] = $addLeague . " | " . $addPlayer;
        }    
        elseif($emptyLeagues>0) {
            $state[$status] = $addPlayer . " | " . $editLeague;
        }  
        elseif($emptyLeagues==0) {
            $state[$status] = $this->translate("make schedule");
        }
        
        return $state;
    }
  
    
    /**
     * adding an user 
     * 
     * @param Request $request
     * @param array $data
     */
    public function addSeason($data)
    {
     
         $season = new Season();
         $season->exchangeArray($data);
         
         $this->getMapper('season')->save($season);
        
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


