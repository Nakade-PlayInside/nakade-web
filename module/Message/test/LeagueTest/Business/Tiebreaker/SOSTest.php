<?php
namespace League\Statistics\Tiebreaker;

use League\Statistics\Tiebreaker\SOS;
use League\Statistics\Results as RESULTS;
use PHPUnit_Framework_TestCase;

class SOSTest extends PHPUnit_Framework_TestCase
{
    
    public function testGetClassDescriptions()
    {
        $object = new SOS();
        
        $this->assertInternalType(
            'string', 
            $object->getDescription(), 
            sprintf('"%s" should return a string', 'getDescription()')
        );
        
        $this->assertInternalType(
            'string', 
            $object->getName(), 
            sprintf('"%s" should be a string', 'getName()')
        );
        
        $this->assertSame(
            'SOS', 
            $object->getName(), 
            sprintf('"%s" do not return the expected string', 'getName()')
        );
    }
    
    public function testSingleton()
    {
       
        $this->assertInstanceOf(
            'League\Statistics\AbstractGameStats', 
            SOS::getInstance(), 
            sprintf('Singleton should be of type "%s"', 'AbstractGameStats')
        );
        
        $this->assertInstanceOf(
            'League\Statistics\Tiebreaker\TiebreakerInterface', 
            SOS::getInstance(), 
            sprintf('Singleton should be of type "%s"', 'TiebreakerInterface')
        );
        
    }
    
    
    /**
     * testing basic functionalty with alle params which result in
     * 0 points.
     */
    public function testGetTieBreakerWithoutPoints()
    {
        
        $matches = array();
        
        $matches[] = array(
            'resultId' => RESULTS::SUSPENDED, 
            'winnerId' => 2
            );
        $matches[] = array(
            'resultId' => null, 
            'winnerId' => 2
            );
      
        $this->makeSingleTest($matches, 0);
        
    }
    
    public function testGetTieBreakerWithPoints()
    {
        
        $matches = array();
        
        $matches[] = array(
            'resultId' => RESULTS::DRAW, 
            'winnerId' => 1
            );
        $matches[] = array(
            'resultId' => RESULTS::BYPOINTS, 
            'winnerId' => 1
            );
        $matches[] = array(
            'resultId' => RESULTS::RESIGNATION, 
            'winnerId' => 1
            );
        $matches[] = array(
            'resultId' => RESULTS::ONTIME, 
            'winnerId' => 1
            );
        $matches[] = array(
            'resultId' => RESULTS::FORFEIT, 
            'winnerId' => 1
            );
      
        $this->makeSingleTest($matches, 1);
        
    }
    
    public function testGetTiebreakerWithThreeWins()
    {
        $playerId=2;
        $mock = array();
        
        $mock[]=$this->getSingleMock(null, $playerId);
       
        $mock[]=$this->getSingleMock(RESULTS::DRAW, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::RESIGNATION, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::SUSPENDED, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::BYPOINTS, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::BYPOINTS, $playerId+1);
        
        //all players are winner-> 3*3wins
        $expected = 9;
        $this->makeArrayTest($mock, $playerId, $expected);
    }
    
    protected function makeSingleTest($matches, $expected)
    {
        $object = new SOS();
        foreach($matches as $match) {
            
            $stub = $this->getSingleMock(
                        $match['resultId'], 
                        $match['winnerId']
                    );
            $mock = array($stub);
            $object->setMatches($mock);
            
            $this->assertSame(
                $object->getTieBreaker(1), 
                $expected, 
                sprintf('"GetTieBreaker()" should return %s',$expected)
            );
        }
    }
    
    
    
    protected function makeArrayTest($mock, $playerId, $expected)
    {
        $object = new SOS();
        $object->setMatches($mock);
        
        $this->assertSame(
            $object->getTieBreaker($playerId), 
            $expected, 
            sprintf('"GetTieBreaker()" should return %s', $expected)
        );
    }
        
    protected function getSingleMock($resultId, $winnerId)
    {
        $stub = $this->getMock(
                'match', 
                array(
                    'getResultId', 
                    'getWinnerId', 
                    'getBlackId',
                    'getWhiteId'
                    ));
        
        $stub->expects($this->any())
             ->method('getResultId')
             ->will($this->returnValue($resultId));
        
        $stub->expects($this->any())
             ->method('getWinnerId')
             ->will($this->returnValue($winnerId));
            
        $stub->expects($this->any())
             ->method('getBlackId')
             ->will($this->returnValue($winnerId));
        
        $stub->expects($this->any())
             ->method('getWhiteId')
             ->will($this->returnValue($winnerId));
        
        return $stub;
    }
    
}