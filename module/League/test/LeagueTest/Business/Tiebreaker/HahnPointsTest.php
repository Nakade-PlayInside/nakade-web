<?php
namespace League\Statistics\Tiebreaker;

use League\Statistics\Tiebreaker\HahnPoints;
use League\Statistics\Results as RESULTS;
use PHPUnit_Framework_TestCase;

class HahnPointsTest extends PHPUnit_Framework_TestCase
{
    
    public function testGetClassDescriptions()
    {
        $object = new HahnPoints();
        
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
            'Hahn', 
            $object->getName(), 
            sprintf('"%s" do not return the expected string', 'getName()')
        );
    }
    
    public function testSingleton()
    {
       
        $this->assertInstanceOf(
            'League\Statistics\AbstractGameStats', 
            HahnPoints::getInstance(), 
            sprintf('Singleton should be of type "%s"', 'AbstractGameStats')
        );
        
        $this->assertInstanceOf(
            'League\Statistics\Tiebreaker\TiebreakerInterface', 
            HahnPoints::getInstance(), 
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
        $matches[] = array(
            'resultId' => RESULTS::DRAW, 
            'winnerId' => 2
            );
        $matches[] = array(
            'resultId' => RESULTS::RESIGNATION, 
            'winnerId' => 2
            );
        $matches[] = array(
            'resultId' => RESULTS::FORFEIT, 
            'winnerId' => 2
            );
        $matches[] = array(
            'resultId' => RESULTS::ONTIME, 
            'winnerId' => 2
            );
      
        $this->makeSingleTest($matches, 0);
        
    }
    
    /**
     * testing basic functionalty with all params
     */
    public function testGetTieBreakerWithMaxPoints()
    {
     
        $matches = array();
        
        $matches[] = array(
            'resultId' => RESULTS::FORFEIT, 
            'winnerId' => 1
            );
        $matches[] = array(
            'resultId' => RESULTS::ONTIME, 
            'winnerId' => 1
            );
        $matches[] = array(
            'resultId' => RESULTS::RESIGNATION, 
            'winnerId' => 1
            );

        $this->makeSingleTest($matches, HahnPoints::MAX_POINTS);
    }
    
    /**
     * testing basic functionalty with all params
     */
    public function testGetTieBreakerWithWinningByPoints()
    {
     
        $matches = array();
        
        $matches[] = array(
            'resultId' => RESULTS::BYPOINTS, 
            'winnerId' => 1
            );
        
        
        $this->makeSingleTest($matches, HahnPoints::OFFSET_POINTS+10);
    }
    
    /**
     * testing basic functionalty with all params
     */
    public function testGetTieBreakerWithLosingByPoints()
    {
     
        $matches = array();
        
        $matches[] = array(
            'resultId' => RESULTS::BYPOINTS, 
            'winnerId' => 2
            );
        
        
        $this->makeSingleTest($matches, 10);
    }
    
    
    
    protected function makeSingleTest($matches, $expected)
    {
        $object = new HahnPoints();
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
    
    public function testGetTieBreakerWithFourWins()
    {
        
        $playerId=2;
        $mock = array();
        
        $mock[]=$this->getSingleMock(null, $playerId);
       
        $mock[]=$this->getSingleMock(RESULTS::DRAW, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::RESIGNATION, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::SUSPENDED, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::BYPOINTS, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::FORFEIT, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::BYPOINTS, $playerId+1);
        
        $expected = 2*HahnPoints::MAX_POINTS + HahnPoints::OFFSET_POINTS +20;
        $this->makeArrayTest($mock, $playerId, $expected);
    }
    
    
    protected function makeArrayTest($mock, $playerId, $expected)
    {
        $object = new HahnPoints();
        $object->setMatches($mock);
        
        $this->assertSame(
            $object->getTieBreaker($playerId), 
            $expected, 
            sprintf('"GetTieBreaker()" should return %s', $expected)
        );
    }
        
    protected function getSingleMock($resultId, $winnerId, $points=10)
    {
        $stub = $this->getMock(
                'match', 
                array(
                    'getResultId', 
                    'getWinnerId', 
                    'getPoints',
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
             ->method('getPoints')
             ->will($this->returnValue($points));
        
        $stub->expects($this->any())
             ->method('getBlackId')
             ->will($this->returnValue($winnerId-1));
        
        $stub->expects($this->any())
             ->method('getWhiteId')
             ->will($this->returnValue($winnerId-1));
        
        return $stub;
    }
    
}