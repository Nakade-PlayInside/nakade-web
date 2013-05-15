<?php
//module/Authentication/src/Authentication/Services/AuthServiceFactory.php
namespace League\Services;

use League\Business\MatchStats;
use League\Mapper\SeasonMapper;
use League\Mapper\LeagueMapper;
use League\Mapper\MatchMapper;
use League\Mapper\TableMapper;
use League\Mapper\PlayerMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;


/**
 * Factory for creating the Zend Authentication Service. Using customized
 * Adapter and Storage instances. 
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
    protected $_table_mapper;
    protected $_player_mapper;
    
    public function getPlayerMapper() 
    {
        return $this->_player_mapper;
    }
    
    public function setPlayerMapper($mapper) 
    {
        $this->_player_mapper=$mapper;
        return $this;
    }
    
    public function getSeasonMapper() 
    {
        return $this->_season_mapper;
    }
    
    public function setSeasonMapper($mapper) 
    {
        $this->_season_mapper=$mapper;
        return $this;
    }
    
    public function getLeagueMapper() 
    {
        return $this->_league_mapper;
    }
    public function setLeagueMapper($mapper) 
    {
        $this->_league_mapper=$mapper;
        return $this;
    }
    
    public function getMatchMapper() 
    {
        return $this->_match_mapper;
    }
    
    public function setMatchMapper($mapper) 
    {
        $this->_match_mapper=$mapper;
        return $this;
    }
    
    public function getTableMapper() 
    {
        return $this->_table_mapper;
    }
    
    public function setTableMapper($mapper) 
    {
        $this->_table_mapper=$mapper;
        return $this;
    }
    /**
     * Creating Zend Authentication Service for logIn and logOut action.
     * Making use of customized adapters for more action as by default.
     * Integration of optional translation feature (i18N)
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return \Zend\Authentication\AuthenticationService
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
        $this->_table_mapper  = new TableMapper($entityManager);
        $this->_player_mapper = new PlayerMapper($entityManager);
        
        //optional translator
        $translator = $services->get('translator');
        $this->setTranslator($translator, $textDomain);
      
        return $this;
        
    }
    
    public function getNoSeasonFoundInfo()
    {
        return $this->translate("No ongoing season found.");
    }        
    
    public function getScheduleTitle($number=1) {
        
        $season = $this->getSeasonMapper()->getActualSeason();
        if($season==null)
            return $this->getNoSeasonFoundInfo ();
              
        $league = $this->getLeagueMapper()
                       ->getLeague($season->getId(), $number);
        return sprintf(
                  "%s %s %s %02d/%d",
                  $this->translate("Matchplan"),
                  $season->getTitle(),
                  $league->getTitle(),
                  $season->getNumber(), 
                  date_format($season->getYear(), 'Y')
              );
        
    }
    
    public function getSchedule($number=1) {
        
        $season = $this->getSeasonMapper()->getActualSeason();
        if($season==null)
            return null;
        
        $league = $this->getLeagueMapper()
                       ->getLeague($season->getId(), $number);
        return $this->getMatchMapper()
                    ->getMatchesInLeague($league->getId());
        
    }
    
    public function getTableShortTitle($number=1) {
       
       $season = $this->getSeasonMapper()->getActualSeason();
       if($season==null)
           return $this->getNoSeasonFoundInfo ();
       
       $league = $this->getLeagueMapper()
                      ->getLeague($season->getId(), $number);
       return sprintf(
                  "%s %02d/%d",
                  $league->getTitle(),
                  $season->getNumber(), 
                  date_format($season->getYear(), 'y')
              );
    }
    
    public function getTableTitle($number=1) {
       
       $season = $this->getSeasonMapper()->getActualSeason();
       if($season==null)
           return $this->getNoSeasonFoundInfo ();
       
       $league = $this->getLeagueMapper()
                      ->getLeague($season->getId(), $number);
       return sprintf(
                  "%s %s %02d/%d",
                  $season->getTitle(),
                  $league->getTitle(),
                  $season->getNumber(), 
                  date_format($season->getYear(), 'y')
              );
    }
   
    public function getTable($number=1) {
       
       $season = $this->getSeasonMapper()->getActualSeason();
       if($season==null)
            return null;
       
       $league = $this->getLeagueMapper()
                      ->getLeague($season->getId(), $number);
       
       return $this->getTableMapper()
                   ->getTableByLeagueId($league->getId());
        
    }
    
    
    public function getCalculatedTable($number=1)
    {
         
       $season = $this->getSeasonMapper()->getActualSeason();
       if($season==null)
            return null;
       
       $league = $this->getLeagueMapper()
                      ->getLeague($season->getId(), $number);
       
       $playersInLeague = $this->getPlayerMapper()
                               ->getAllPlayersInLeague($league->getId());
      
       
       $allMatches = $this->getMatchMapper()
                          ->getAllMatchesWithResult($league->getId());
       
       $match = new MatchStats($allMatches);
       
       foreach ($playersInLeague as $player) {
           $data = $match->getGamesStats($player->getUid());
           $player->populate($data);
          
       }    
       
       usort($playersInLeague, function($a, $b)
       {
            if($a->getGamesPoints()==$b->getGamesPoints())
                return ($a->getFirstTiebreak()>$b->getFirstTiebreak())?-1:1;
       
            return ($a->getGamesPoints()>$b->getGamesPoints())?-1:1;
       });
       return $playersInLeague;
    }    
    
   
   
    
}


