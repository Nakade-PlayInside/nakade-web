<?php
//module/Authentication/src/Authentication/Services/AuthServiceFactory.php
namespace League\Services;

use League\Services\ActualSeasonServiceFactory;
use PHPUnit_Framework_TestCase;
use League\Mapper\MatchMapper;
use League\Mapper\TableMapper;
use League\Mapper\LeagueMapper;
use League\Mapper\SeasonMapper;

/**
 * Factory for creating the Zend Authentication Service. Using customized
 * Adapter and Storage instances. 
 * 
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class ActualSeasonServiceFactoryTest extends PHPUnit_Framework_TestCase 
{
   
    protected $data = array();
    public function __construct() {
        
         $em = $this->getEntityMock();
         $this->data = array(
            'matchMapper'  => new MatchMapper($em),
            'seasonMapper' => new SeasonMapper($em),
            'leagueMapper' => new LeagueMapper($em),
            'tableMapper'  => new TableMapper($em),
        );
        
    }
    
    protected function getEntityMock()
    {
        return $this->getMock(
               'EntityManager',
               array()
               );
    }    
            
    public function testInitialState()
    {
        $object = new ActualSeasonServiceFactory();

        foreach($this->data as $key => $value) {
            
            $method = 'get'.ucfirst($key);
            $this->assertNull(
                $object->$method(), 
                sprintf('"%s" should initially be null', $key)
            );
        } 
    }
    
    public function testSetsPropertiesCorrectly()
    {
        $object = new ActualSeasonServiceFactory();
        
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
    
    public function testMethodGetTableReturnNull()
    {
       $object = new ActualSeasonServiceFactory();
       
       $season = $this->getMock(
            'SeasonMapper',
             array('getActualSeason')
             );
       
       $season->expects($this->once())
            ->method('getActualSeason')
            ->will($this->returnValue(null));
       
       $object->setSeasonMapper($season);
       
       $this->assertNull(
        $object->getTable()
       );        
       
       
    }
}


