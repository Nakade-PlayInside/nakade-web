<?php
//module/Authentication/src/Authentication/Services/AuthServiceFactory.php
namespace League\Services;

use League\Statistics\MatchStats;
use League\Statistics\Sorting\PlayerSorting as SORT;
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
class ActualSeasonServiceFactory extends AbstractService
{
   
    
    protected $_match_stats;
   
    
    public function setMatchStats($stats)
    {
        $this->_match_stats=$stats;
        return $this;
    }
    
    public function getMatchStats()
    {
        return $this->_match_stats;
    }     
    
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
        $this->_match_stats   = new MatchStats();
        
        //optional translator
        $translator = $services->get('translator');
        $this->setTranslator($translator, $textDomain);
      
        return $this;
        
    }
    
    /**
     * Get the head title of the league schedule
     * @param int $lid
     * @return string
     */
    public function getScheduleTitle($uid=null) {
        
        $title = $this->getTitle($uid);
        if(null===$title)
            return null;
        
        return $this->translate("Matchplan"). " " . $title;
        
    }
    
    /**
     * Get league title
     * 
     * @param int $uid
     * @return mixed null|string
     */
    public function getTitle($uid=null) {
        
        $season = $this->getActualSeason();
        if(null===$season)
            return null;
        
        //get user's league. 
        //if not in any league, the top league is returned
        $league = $this->getUserLeague($season, $uid);
        if(null===$league)
            return null;
         
        return sprintf(
                  "%s.%s - %s %02d/%d",
                  $league->getNumber(),
                  $this->translate("League"),
                  $this->translate("Season"),
                  $season->getNumber(), 
                  date_format($season->getYear(), 'Y')
              );
        
    }
    
    /**
     * get the user's league. If user is not in any league,
     * the top league is returned instead.
     * 
     * @param Season $season
     * @param int $uid
     * @return League
     */
    public function getUserLeague($season, $uid) 
    {
        $seasonId = $season->getId();
        $league   = $this->getMapper('match')
                         ->getLeagueInSeasonByPlayer($seasonId, $uid);
        
        if(null===$league)
            $league = $this->getMapper('league')
                           ->getLeague($seasonId, 1);
        
        return $league;
        
    }
    
    /**
     * Get the user's schedule of a league. If user is not in a league,
     * the schedule of the top league is returned instead.
     * 
     * @param int $uid
     * @return mixed null|League
     */
    public function getSchedule($uid) {
        
        $season = $this->getActualSeason();
        if(null===$season)
            return null;
        
        //get user's league. 
        //if not in any league, the top league is returned
        $league = $this->getUserLeague($season, $uid);
        if(null===$league)
            return null;
        
        
        return $this->getMapper('match')
                    ->getMatchesInLeague($league->getId());
        
    }
    
    
    /**
     * Get the head title for a league table
     * @param int $uid
     * @return string
     */
    public function getTableTitle($uid) {
       
       $title = $this->getTitle($uid);
        if(null===$title)
            return null;
        
        return $this->translate("Table"). " " . $title;
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
     * get the Top league standings
     * 
     * @return mixed null|Participants
     */
    public function getTopLeagueTable() 
    {
        
        $season = $this->getActualSeason();
        if(null===$season)
            return null;
        
        $lid = $this->getMapper('league')
                    ->getLeague($season->getId(), 1)
                    ->getId();
        
        return $this->getLeagueTable($lid);
    }

    /**
     * Get a sorted league table.
     * @param int $lid
     * @param string $sort
     * @return array
     */
    public function getLeagueTable($uid, $sort=SORT::BY_POINTS)
    {
        
        $season = $this->getActualSeason();
        if(null===$season)
            return null;
       
       //get user's league. 
       //if not in any league, the top league is returned
       $league = $this->getUserLeague($season, $uid);
       if(null===$league)
           return null;
       
       $lid=$league->getId();
       $playersInLeague = $this->getMapper('player')
                               ->getAllPlayersInLeague($lid);
      
       $allMatches      = $this->getMapper('match')
                               ->getAllMatchesWithResult($lid);
       
       $this->getMatchStats()->populateRules(
               $season->getArrayCopy()
       );
       $this->getMatchStats()->setMatches($allMatches);
       $this->getMatchStats()->setPlayers($playersInLeague);
       
       $players = $this->getMatchStats()->getMatchStats();
       
       $sorting = SORT::getInstance();
       $sorting->sorting($players, $sort);
       
       return $players;
       
    }  
    
    /**
     * getting the names of all tiebreakers as an array.
     * make sure you have previously requested the league table dependend
     * on this.
     * @return array
     */
    public function getTiebreakerNames() 
    {
        $season = $this->getActualSeason();
        if(null===$season)
            return null;
       
        $names = array();
        
        $names[] = $season->getTiebreaker1();
        $names[] = $season->getTiebreaker2();
        $names[] = $season->getTiebreaker3();
        return $names;
    }
   
   
 
}


