<?php
namespace League\Statistics;

use League\Statistics\MatchStats;
use League\Entity\Participants;
use PHPUnit_Framework_TestCase;

class MatchStatsTest extends PHPUnit_Framework_TestCase
{
    
    protected $data  = array(
            'matches' => array('matches'),
            'players'  => array('players'),
            'tiebreakerFactory'  => array('factory'),
            'gamesStatsFactory'  => array('factory'),
            'drawPoints' => 1,
            'winPoints' => 3,
            'tiebreaker1' => 'SOS',
            'tiebreaker2' => 'SOSOS',
            'tiebreaker3' => 'Buchholz',
    );
    
    protected $rules = array(
            '_drawPoints' => 1,
            '_winPoints' => 2,
            '_tiebreaker1' => 'Buchholz',
            '_tiebreaker2' => 'MacMahon',
            '_tiebreaker3' => 'Shuzako'
    );
    
    public function testInitialState()
    {
        $object = new MatchStats();
         
        $this->assertNull(
                $object->getMatches(), 
                sprintf('"%s" should return null', 'getMatches()')
        );
        
        $this->assertNull(
                $object->getPlayers(), 
                sprintf('"%s" should return null', 'getPlayers()')
        );
        
        $this->assertSame(
                $object->getDrawPoints(),
                0,
                sprintf('"%s" should return "0"', 'getDrawPoints()')
        );
        
        $this->assertSame(
                $object->getWinPoints(),
                1,
                sprintf('"%s" should return "1"', 'getWinPoints()')
        );
        
        $this->assertSame(
                $object->getTiebreaker1(),
                'Hahn',
                sprintf('"%s" should return "Hahn"', 'getTiebreaker1()')
        );
        
        $this->assertSame(
                $object->getTiebreaker2(),
                'SODOS',
                sprintf('"%s" should return "SODOS"', 'getTiebreaker2()')
        );
        
        $this->assertSame(
                $object->getTiebreaker3(),
                'CUSS',
                sprintf('"%s" should return "CUSS"', 'getTiebreaker3()')
        );
        
        
    }
    
    public function testSetsPropertiesCorrectly()
    {
        $object = new MatchStats();
        
        foreach($this->data as $key => $value) {
            
            //setValue
            $method = 'set'.ucfirst($key);
            $object->$method($value);
            
            //getValue
            $method = 'get'.ucfirst($key);
            $this->assertSame(
                $value, 
                $object->$method(), 
                sprintf('"%s" was not set correctly', $key)
            );
        } 
        
    }
    
    public function testPopulate()
    {
        $object = new MatchStats();
        $object->populateRules($this->rules);
       
        foreach($this->rules as $key => $value) {
            
            //getValue
            $method = 'get'.ucfirst(str_replace('_', '',$key));
            $this->assertSame(
                $value, 
                $object->$method(), 
                sprintf('"%s" was not set correctly', $key)
            );
        } 
       
    }
    
    public function testGetMatchStats()
    {
        $object = new MatchStats();
       
        //player array uid=1
        $player = new Participants();
        $player->setUid(1);
        $object->setPlayers(array($player));
        
        //gamesstats factory... returns always 1
        $factoryMock = $this->getMock(
                'Factory', 
                array('getPoints', 'setPlayerId'));
        $factoryMock->expects($this->any())
             ->method('getPoints')
             ->will($this->returnValue(1));
        
        $object->setGamesStatsFactory($factoryMock);
        $object->setTiebreakerFactory($factoryMock);
       
        $object->setDrawPoints(1);
        $object->setWinPoints(3);
        $players = $object->getMatchStats();
        
        
        $this->assertSame(
            $players[0]->getGamesPlayed(), 
            1, 
            sprintf('"%s" was not set correctly', 'getGamesPlayed()')
        );
         $this->assertSame(
            $players[0]->getGamesSuspended(), 
            1, 
            sprintf('"%s" was not set correctly', 'getGamesSuspended()')
        );
        $this->assertSame(
            $players[0]->getGamesWin(), 
            1, 
            sprintf('"%s" was not set correctly', 'getGamesWin()')
        );
        $this->assertSame(
            $players[0]->getGamesDraw(), 
            1, 
            sprintf('"%s" was not set correctly', 'getGamesDraw()')
        );
        $this->assertSame(
            $players[0]->getGamesLost(), 
            1, 
            sprintf('"%s" was not set correctly', 'getGamesLost()')
        );
        
        $this->assertSame(
            $players[0]->getFirstTiebreak(), 
            1, 
            sprintf('"%s" was not set correctly', 'getFirstTiebreak()')
        );
        $this->assertSame(
            $players[0]->getSecondTiebreak(), 
            1, 
            sprintf('"%s" was not set correctly', 'getSecondTiebreak()')
        );
        $this->assertSame(
            $players[0]->getThirdTiebreak(), 
            1, 
            sprintf('"%s" was not set correctly', 'getThirdTiebreak()')
        );
        $this->assertSame(
            $players[0]->getGamesPoints(), 
            4, 
            sprintf('"%s" was not set correctly', 'getGamesPoints()')
        );
         
    }   
    
    
    
    
}