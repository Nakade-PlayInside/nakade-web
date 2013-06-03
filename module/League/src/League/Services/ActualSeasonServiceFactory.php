<?php
//module/Authentication/src/Authentication/Services/AuthServiceFactory.php
namespace League\Services;

use League\Statistics\MatchStats;
use League\Statistics\Sorting\PlayerSorting as SORT;
use League\Mapper\SeasonMapper;
use League\Mapper\LeagueMapper;
use League\Mapper\MatchMapper;
use League\Mapper\PlayerMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;


/**
 * Factory for the actual season for receiving
 * sorted league tables and schedules.
 * 
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class ActualSeasonServiceFactory 
    extends AbstractTranslationService 
    implements FactoryInterface 
{
   
    protected $_season_mapper;
    protected $_league_mapper;
    protected $_match_mapper;
    protected $_player_mapper;
    protected $_match_stats;
   
    public function setSeasonMapper($mapper)
    {
        $this->_season_mapper=$mapper;
        return $this;
    }
    
    public function getSeasonMapper()
    {
        return $this->_season_mapper;
    }       
    
    public function setLeagueMapper($mapper)
    {
        $this->_league_mapper=$mapper;
        return $this;
    }
    
    public function getLeagueMapper()
    {
        return $this->_league_mapper;
    }      
    
    public function setMatchMapper($mapper)
    {
        $this->_match_mapper=$mapper;
        return $this;
    }
    
    public function getMatchMapper()
    {
        return $this->_match_mapper;
    }      
    
    public function setPlayerMapper($mapper)
    {
        $this->_player_mapper=$mapper;
        return $this;
    }
    
    public function getPlayerMapper()
    {
        return $this->_player_mapper;
    }     
    
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
         
        //EntityManager for database access by doctrine
        $entityManager = $services->get('Doctrine\ORM\EntityManager');
        
        if (null === $entityManager) {
            throw new RuntimeException(
                sprintf('Entity manager could not be found in service.')
            );
        }
        
        $this->_season_mapper = new SeasonMapper($entityManager);
        $this->_league_mapper = new LeagueMapper($entityManager);
        $this->_match_mapper  = new MatchMapper($entityManager);
        $this->_player_mapper = new PlayerMapper($entityManager);
        $this->_match_stats   = new MatchStats();
        
        //optional translator
        $translator = $services->get('translator');
        $this->setTranslator($translator, $textDomain);
      
        return $this;
        
    }
    
    /**
     * Get a translated message 
     * @return string
     */
    public function getNoSeasonFoundInfo()
    {
        return $this->translate("No ongoing season found.");
    }        
    
    /**
     * Get the head title of the league schedule
     * @param int $lid
     * @return string
     */
    public function getScheduleTitle($lid) {
        
        /**
         * todo: titel zusammensetzung in den mapper packen
         * verkürzen auf eine methode.
         */
        $season = $this->getSeasonMapper()->getActualSeason();
        if($season==null)
            return $this->getNoSeasonFoundInfo ();
              
        $league = $this->getLeagueMapper()
                      ->getLeagueById($lid);
         
        return sprintf(
                  "%s %s %s %02d/%d",
                  $this->translate("Matchplan"),
                  $season->getTitle(),
                  $league->getTitle(),
                  $season->getNumber(), 
                  date_format($season->getYear(), 'Y')
              );
        
    }
    
    /**
     * Get the schedule of a league
     * @param int $lid
     * @return mixed
     */
    public function getSchedule($lid) {
        
        return $this->getMatchMapper()
                    ->getMatchesInLeague($lid);
        
    }
    
    /**
     * Get a short title for a league table
     * @param int $lid
     * @return string
     */
    public function getTableShortTitle($lid) {
       
       $season = $this->getSeasonMapper()->getActualSeason();
       if($season==null)
           return $this->getNoSeasonFoundInfo ();
       
       $league = $this->getLeagueMapper()
                      ->getLeagueById($lid);
       
       return sprintf(
                  "%s %02d/%d",
                  $league->getTitle(),
                  $season->getNumber(), 
                  date_format($season->getYear(), 'y')
              );
    }
    
    /**
     * Get the head title for a league
     * @param int $lid
     * @return string
     */
    public function getTableTitle($lid) {
       
       $season = $this->getSeasonMapper()->getActualSeason();
       if($season==null)
           return $this->getNoSeasonFoundInfo ();
       
       $league = $this->getLeagueMapper()
                      ->getLeagueById($lid);
       
       return sprintf(
                  "%s %s %02d/%d",
                  $season->getTitle(),
                  $league->getTitle(),
                  $season->getNumber(), 
                  date_format($season->getYear(), 'y')
              );
    }
   
    /**
     * Get the top league of the actual season
     * @return mixed
     */
    public function getTopLeagueId()
    {
       $season = $this->getSeasonMapper()->getActualSeason();
       if($season==null)
            return null;
       
       return $this->getLeagueMapper()
                   ->getLeague($season->getId(), 1)
                   ->getId();
       
    }        


    /**
     * Get a sorted league table.
     * @param int $lid
     * @param string $sort
     * @return array
     */
    public function getLeagueTable($lid, $sort=SORT::BY_POINTS)
    {
       $playersInLeague = $this->getPlayerMapper()
                               ->getAllPlayersInLeague($lid);
      
       $allMatches = $this->getMatchMapper()
                          ->getAllMatchesWithResult($lid);
       
       $season = $this->getSeasonMapper()
                      ->getSeasonByLeagueId($lid); 
       
       $this->getMatchStats()->populateRules(
               $season->getRules()
                      ->getArrayCopy()
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
    public function getTiebreakerNames($lid) 
    {
        $names = array();
        
        $season = $this->getSeasonMapper()
                       ->getSeasonByLeagueId($lid); 
        
        $names[] = $season->getRules()->getTiebreaker1();
        $names[] = $season->getRules()->getTiebreaker2();
        $names[] = $season->getRules()->getTiebreaker3();
        return $names;
    }
   
   
 
}


