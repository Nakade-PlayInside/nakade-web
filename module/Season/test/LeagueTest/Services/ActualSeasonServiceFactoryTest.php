<?php
//module/Authentication/src/Authentication/Services/AuthServiceFactory.php
namespace League\Services;

use League\Services\ActualSeasonServiceFactory;
use PHPUnit_Framework_TestCase;


/**
 * Factory for creating the Zend Authentication Services. Using customized
 * Adapter and Storage instances.
 *
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class ActualSeasonServiceFactoryTest extends PHPUnit_Framework_TestCase
{

    protected $data = array(
            'matchMapper'  => array('mock'),
            'seasonMapper' => array('mock'),
            'leagueMapper' => array('mock'),
            'playerMapper' => array('mock'),
            'matchStats'   => array('mock'),

    );



    public function testInitialState()
    {

        $object = new ActualSeasonServiceFactory();

        $keys = array_keys($this->data);
        foreach($keys as $key) {

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

    public function testGetNoSeasonFoundInfo()
    {
      $object = new ActualSeasonServiceFactory();

      $this->assertInternalType(
            'string',
            $object->getNoSeasonFoundInfo(),
            sprintf('"%s" should be a string', 'getNoSeasonFoundInfo()')
        );

    }

    public function testGetScheduleTitle()
    {
      $object = $this->getActualSeasonNullMock();

      $this->assertSame(
            'No ongoing season found.',
            $object->getScheduleTitle(1),
            sprintf('"No ongoing season found" was the expected string')
      );


    }

    protected function getActualSeasonNullMock()
    {
       $object = new ActualSeasonServiceFactory();

        $seasonMock = $this->getMock(
              'seasonMapper',
              array('getActualSeason')
        );

        $seasonMock->expects($this->any())
             ->method('getActualSeason')
             ->will($this->returnValue(null));

         $object->setSeasonMapper($seasonMock);

         return $object;

    }

    public function testGetSchedule()
    {
      $object = new ActualSeasonServiceFactory();

      $mock = $this->getMock(
              'matchMapper',
              array('getMatchesInLeague')
      );

      $mock->expects($this->any())
             ->method('getMatchesInLeague')
             ->will($this->returnValue(2));

      $object->setMatchMapper($mock);

      $this->assertSame(
            2,
            $object->getSchedule(1),
            sprintf('"%s" was the expected return value', 2)
      );

    }

     public function testGetTableShortTitle()
    {
      $object = $this->getActualSeasonNullMock();

      $this->assertSame(
            'No ongoing season found.',
            $object->getTableShortTitle(1),
            sprintf('"No ongoing season found" was the expected string')
      );



    }


    public function testGetTableTitle()
    {
       $object = $this->getActualSeasonNullMock();

      $this->assertSame(
            'No ongoing season found.',
            $object->getTableTitle(1),
            sprintf('"No ongoing season found" was the expected string')
      );

    }


    public function testGetTopLeagueId()
    {

       $object = $this->getActualSeasonNullMock();

      $this->assertNull(
            $object->getTopLeagueId(),
            sprintf('"null" was expected')
      );

    }

}


