<?php
namespace League\Statistics\Tiebreaker;

use League\Statistics\Tiebreaker\TiebreakerFactory;
use PHPUnit_Framework_TestCase;

class TiebreakerFactoryTest extends PHPUnit_Framework_TestCase
{
    protected $_tiebreakers = array(
                'Hahn', 
                'CUSS', 
                'SOS', 
                'SODOS',
              );
    
    
    public function testInitialState()
    {
        $object = new TiebreakerFactory($this->getMatchesMock());
            
        $this->assertNull(
            $object->getPlayerId(), 
            sprintf('"%s" should initially be null', 'playersId()')
            
        );
    }
    
    public function testSetsPropertiesCorrectly()
    {
        $object = new TiebreakerFactory($this->getMatchesMock());
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
        $object = new TiebreakerFactory($this->getMatchesMock());
        $object->getPoints('cuss');
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testTiebreakerNotFoundExceptions()
    {
        $object = new TiebreakerFactory($this->getMatchesMock());
        $object->setPlayerId(1);
        $object->getPoints('myexception');
    }
    
    /**
     * testing basic functionalty with alle params which result in
     * 0 points.
     */
    public function testTiebreakers()
    {
        
        $object = new TiebreakerFactory($this->getMatchesMock());
        $object->setPlayerId(1);
        
        foreach($this->_tiebreakers as $tie) {
            
            $this->assertSame(
                $object->getPoints($tie), 
                0, 
                sprintf('"GetPoints()" should return 0')
            );
        }
    }
    
    /**
     * testing basic functionalty with alle params which result in
     * 0 points.
     */
    public function testTiebreakerName()
    {
        
        $object = new TiebreakerFactory($this->getMatchesMock());
        $object->setPlayerId(1);
        
        foreach($this->_tiebreakers as $tie) {
           
            $name = strtolower($object->getName($tie));
            $this->assertSame(
                $name, 
                strtolower($tie), 
                sprintf('"%s" is not the expected name of Tiebreaker "%s"'
                    ,$name
                    ,$tie
                )
            );
        }
    }
   
    
        
    protected function getMatchesMock()
    {
        $stub = $this->getMock(
                'match', 
                array('getResultId', 'getWinnerId'));
        
        $stub->expects($this->any())
             ->method('getResultId')
             ->will($this->returnValue(null));
        
        $stub->expects($this->any())
             ->method('getWinnerId')
             ->will($this->returnValue(2));
        
        return array($stub);
    }
    
}