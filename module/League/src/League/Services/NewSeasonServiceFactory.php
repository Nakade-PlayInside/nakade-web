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
class NewSeasonServiceFactory 
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
    public function getActualSeason()
    {
        return  $this->getSeasonMapper()->getActualSeason();
    }  
    
    /**
     * Get number of open matches in a season 
     * @return string
     */
    public function getOpenMatches()
    {
        $id=$this->getSeasonMapper()->getActualSeason()->getId();
        
        return $this->getMatchMapper()->getOpenMatches($id);
        
     } 
     
      /**
     * Get last mtch date in a season 
     * @return string
     */
    public function getLastMatchDate()
    {
        $id=$this->getSeasonMapper()->getActualSeason()->getId();
        
        return $this->getMatchMapper()->getLastMatchDate($id);
        
     } 
     
     public function getLastSeason()
    {
        return $this->getSeasonMapper()->getLastSeason();
        
     } 
    
    
    
    
   
 
}


