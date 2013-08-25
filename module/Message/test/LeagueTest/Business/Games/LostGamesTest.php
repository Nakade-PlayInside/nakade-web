<?php
namespace League\Statistics\Games;

use League\Statistics\Games\LostGames;
use League\Statistics\Results as RESULTS;
use PHPUnit_Framework_TestCase;

class LostGamesTest extends PHPUnit_Framework_TestCase
{
    
    public function testSingleton()
    {
       
        $this->assertInstanceOf(
            'League\Statistics\AbstractGameStats', 
            LostGames::getInstance(), 
            sprintf('Singleton should be of type "%s"', 'AbstractGameStats')
        );
        
        $this->assertInstanceOf(
            'League\Statistics\Games\GameStatsInterface', 
            LostGames::getInstance(), 
            sprintf('Singleton should be of type "%s"', 'GameStatsInterface')
        );
        
    }
    
    
    /**
     * testing basic functionalty with alle params which result in
     * 0 points.
     */
    public function testGetNumberOfGamesWithoutPoints()
    {
        
        $matches = array();
        
        
        $matches[] = array(
            'resultId' => null, 
            'playerId' => 2,
            'winnerId' => 1,
            );
        $matches[] = array(
            'resultId' => RESULTS::SUSPENDED, 
            'playerId' => 2,
            'winnerId' => 1,
            );
        $matches[] = array(
            'resultId' => RESULTS::DRAW, 
            'playerId' => 2,
            'winnerId' => 1,
            );
        $matches[] = array(
            'resultId' => RESULTS::RESIGNATION, 
            'playerId' => 2,
            'winnerId' => 1,
            );
        $matches[] = array(
            'resultId' => RESULTS::BYPOINTS, 
            'playerId' => 2,
            'winnerId' => 1,
            );
        $matches[] = array(
            'resultId' => RESULTS::FORFEIT, 
            'playerId' => 2,
            'winnerId' => 1,
            );
        $matches[] = array(
            'resultId' => RESULTS::ONTIME, 
            'playerId' => 2,
            'winnerId' => 1,
            );

        $this->makeSingleTest($matches, 0);

    }
    
    /**
     * testing basic functionalty with alle params which result in
     * 1 points.
     */
    public function testGetNumberOfGamesWithPoints()
    {
        $matches = array();
       
        $matches[] = array(
            'resultId' => RESULTS::BYPOINTS, 
            'playerId' => 1,
            'winnerId' => 2,
            );
        $matches[] = array(
            'resultId' => RESULTS::RESIGNATION, 
            'playerId' => 1,
            'winnerId' => 2,
            );
        $matches[] = array(
            'resultId' => RESULTS::FORFEIT, 
            'playerId' => 1,
            'winnerId' => 2,
            );
        $matches[] = array(
            'resultId' => RESULTS::ONTIME, 
            'playerId' => 1,
            'winnerId' => 2,
            );
        
        $this->makeSingleTest($matches, 1);
        
    }
    
    public function testGetNumberOfGamesWithThreePoints()
    {
       
        $playerId=2;
        $mock = array();
        
        $mock[]=$this->getSingleMock(null, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::DRAW, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::RESIGNATION, $playerId, 1);
        $mock[]=$this->getSingleMock(RESULTS::SUSPENDED, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::BYPOINTS, $playerId, 1);
        $mock[]=$this->getSingleMock(RESULTS::BYPOINTS, $playerId, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::FORFEIT, $playerId, 1);
        $mock[]=$this->getSingleMock(RESULTS::FORFEIT, $playerId, $playerId);
        $mock[]=$this->getSingleMock(RESULTS::SUSPENDED, $playerId);
        
        $expected = 3;
        $this->makeArrayTest($mock, $playerId, $expected);
    }
    
    
    protected function makeSingleTest($matches, $expected)
    {
        $object = new LostGames();
        foreach($matches as $match) {
            
            $stub = $this->getSingleMock(
                        $match['resultId'], 
                        $match['playerId'],
                        $match['winnerId']
                    );
            $mock = array($stub);
            $object->setMatches($mock);
            
            $this->assertSame(
                $object->getNumberOfGames(1), 
                $expected, 
                sprintf('"GetNumberOfGames()" should return %s',$expected)
            );
        }
    }
    
     protected function makeArrayTest($mock, $playerId, $expected)
    {
        $object = new LostGames();
        $object->setMatches($mock);
        
        $this->assertSame(
            $object->getNumberOfGames($playerId), 
            $expected, 
            sprintf('"getNumberOfGames()" should return %s', $expected)
        );
    }
        
    protected function getSingleMock($resultId, $looserId, $winnerId=1)
    {
        $stub = $this->getMock(
                'match', 
                array('getResultId','getWinnerId','getBlackId','getWhiteId'));
        
        $stub->expects($this->any())
             ->method('getResultId')
             ->will($this->returnValue($resultId));
        
        $stub->expects($this->any())
             ->method('getWinnerId')
             ->will($this->returnValue($winnerId));
        
        $stub->expects($this->any())
             ->method('getBlackId')
             ->will($this->returnValue($looserId));
        
        $stub->expects($this->any())
             ->method('getWhiteId')
             ->will($this->returnValue($looserId));
        
        return $stub;
    }
    
}