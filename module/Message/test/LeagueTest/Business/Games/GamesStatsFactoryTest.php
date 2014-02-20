<?php
namespace League\Statistics\Tiebreaker;

use League\Statistics\Games\GamesStatsFactory;
use PHPUnit_Framework_TestCase;

class GamesStatsFactoryTest extends PHPUnit_Framework_TestCase
{
    protected $_gamestats = array(
                'played', 
                'lost', 
                'won', 
                'draw',
                'suspended',
              );
    
    
    public function testInitialState()
    {
        $object = new GamesStatsFactory($this->getMatchesMock());
            
        $this->assertNull(
            $object->getPlayerId(), 
            sprintf('"%s" should initially be null', 'playersId()')
            
        );
    }
    
    public function testSetsPropertiesCorrectly()
    {
        $object = new GamesStatsFactory($this->getMatchesMock());
        $object->setPlayerId(1);
        
        $this->assertSame(
            $object->getPlayerId(), 
            1,   
            sprintf('"%s" was not set correctly', 'playersId()')
            
        );
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testPlayerNotSetExceptions()
    {
        $object = new GamesStatsFactory($this->getMatchesMock());
        $object->getPoints('played');
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testTiebreakerNotFoundExceptions()
    {
        $object = new GamesStatsFactory($this->getMatchesMock());
        $object->setPlayerId(1);
        $object->getPoints('myexception');
    }
    
    /**
     * testing basic functionalty with alle params which result in
     * 0 points.
     */
    public function testGameStats()
    {
      
        $object = new GamesStatsFactory($this->getMatchesMock());
        $object->setPlayerId(1);
        
        foreach($this->_gamestats as $stats) {
            
            $this->assertSame(
                $object->getPoints($stats), 
                0, 
                sprintf('"GetPoints()" should return 0')
            );
        }
    }
    
        
    protected function getMatchesMock()
    {
        $stub = $this->getMock(
                'match', 
                array('getResultId', 'getWinnerId', 'getBlackId','getWhiteId'));
        
        $stub->expects($this->any())
             ->method('getResultId')
             ->will($this->returnValue(null));
        
        $stub->expects($this->any())
             ->method('getWinnerId')
             ->will($this->returnValue(2));
        
        return array($stub);
    }
    
}