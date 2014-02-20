<?php
namespace League\Statistics\Sorting;

use League\Statistics\Sorting\PlayerSorting;
use League\Entity\Participants;
use PHPUnit_Framework_TestCase;

/**
 * Test all sorting methods on its won. Finally the functionality
 * is tested by using a dataset in a given order, shuffle the array and
 * sort it in the correct order again.
 */
class PlayerSortingTest extends PHPUnit_Framework_TestCase
{
    
    public function testSingleton()
    {
       
        $this->assertInstanceOf(
            'League\Statistics\Sorting\PlayerSorting', 
            PlayerSorting::getInstance(), 
            sprintf('Singleton should be of type "%s"', 'PlayerSorting')
        );
        
    }
    
    public function testSortByName()
    {
       
        $object = new PlayerSorting();
        
        $players = array();
        
        $players[] = $this->getNameMock('Peter');
        $players[] = $this->getNameMock('Hans');
        $players[] = $this->getNameMock('Tim');
        
        shuffle($players);
        $object->sorting($players, PlayerSorting::BY_NAME);
        
        $sortby='Name';
        $this->makeSortingTest(
                $players[0]->getPlayer()
                           ->getShortName(), 
                           'Hans', $sortby);
        
         $this->makeSortingTest(
                $players[1]->getPlayer()
                           ->getShortName(), 
                           'Peter', $sortby);
         
         $this->makeSortingTest(
                $players[2]->getPlayer()
                           ->getShortName(), 
                           'Tim', $sortby);
        
        
    }
    
    
    protected function getNameMock($name)
    {
        $player = $this->getPlayerMock('gamesPlayed',5);
        
        $stub = $this->getMock(
                'player', 
                array('getShortName'));
        
        $stub->expects($this->any())
             ->method('getShortName')
             ->will($this->returnValue($name));
        
        $player->setPlayer($stub);
        return $player;
        
    }
    public function testSortByPlayedGames()
    {
       
        $object = new PlayerSorting();
        
        $players = array();
        
        $players[] = $this->getPlayerMock('gamesPlayed',4);
        $players[] = $this->getPlayerMock('gamesPlayed',3);
        $players[] = $this->getPlayerMock('gamesPlayed',6);

        shuffle($players);
        $object->sorting($players, PlayerSorting::BY_PLAYED_GAMES);
        
        $sortby='Played Games';
        $this->makeSortingTest($players[0]->getGamesPlayed(), 6, $sortby);
        $this->makeSortingTest($players[1]->getGamesPlayed(), 4, $sortby);
        $this->makeSortingTest($players[2]->getGamesPlayed(), 3, $sortby);
        
    }
    
    public function testSortByPoints()
    {
       
        $object = new PlayerSorting();
        
        $players = array();
        
        $players[] = $this->getPlayerMock('gamesPoints',4);
        $players[] = $this->getPlayerMock('gamesPoints',7);
        $players[] = $this->getPlayerMock('gamesPoints',6);

        shuffle($players);
        $object->sorting($players, PlayerSorting::BY_POINTS);
        
        $sortby='Points';
        $this->makeSortingTest($players[0]->getGamesPoints(), 7, $sortby);
        $this->makeSortingTest($players[1]->getGamesPoints(), 6, $sortby);
        $this->makeSortingTest($players[2]->getGamesPoints(), 4, $sortby);
        
    }
    
    public function testSortBySuspended()
    {
       
        $object = new PlayerSorting();
        $players = array();
        
        $players[] = $this->getPlayerMock('gamesSuspended',1);
        $players[] = $this->getPlayerMock('gamesSuspended',8);
        $players[] = $this->getPlayerMock('gamesSuspended',7);
        $players[] = $this->getPlayerMock('gamesSuspended',9);
        
        shuffle($players);
        $object->sorting($players, PlayerSorting::BY_SUSPENDED_GAMES);
       
        $sortby='Suspended Games';
        $this->makeSortingTest($players[0]->getGamesSuspended(), 9,$sortby);
        $this->makeSortingTest($players[1]->getGamesSuspended(), 8, $sortby);
        $this->makeSortingTest($players[2]->getGamesSuspended(), 7, $sortby);
        $this->makeSortingTest($players[3]->getGamesSuspended(), 1, $sortby);
        
    }
    
    public function testSortByDraw()
    {
       
        $object = new PlayerSorting();
        
        $players = array();
        
        $players[] = $this->getPlayerMock('gamesDraw',5);
        $players[] = $this->getPlayerMock('gamesDraw',7);
        $players[] = $this->getPlayerMock('gamesDraw',6);

        shuffle($players);
        $object->sorting($players, PlayerSorting::BY_DRAW_GAMES);
        
        $sortby='Draw Games';
        $this->makeSortingTest($players[0]->getGamesDraw(), 7, $sortby);
        $this->makeSortingTest($players[1]->getGamesDraw(), 6, $sortby);
        $this->makeSortingTest($players[2]->getGamesDraw(), 5, $sortby);
        
    }
    
    public function testSortByLost()
    {
       
        $object = new PlayerSorting();
        
        $players = array();
        
        $players[] = $this->getPlayerMock('gamesLost',7);
        $players[] = $this->getPlayerMock('gamesLost',4);
        $players[] = $this->getPlayerMock('gamesLost',9);

        shuffle($players);
        $object->sorting($players, PlayerSorting::BY_LOST_GAMES);
        
        $sortby='Lost Games';
        $this->makeSortingTest($players[0]->getGamesLost(), 9, $sortby);
        $this->makeSortingTest($players[1]->getGamesLost(), 7, $sortby);
        $this->makeSortingTest($players[2]->getGamesLost(), 4, $sortby);
        
    }
    
    public function testSortByWon()
    {
       
        $object = new PlayerSorting();
        
        $players = array();
        
        $players[] = $this->getPlayerMock('gamesWin',1);
        $players[] = $this->getPlayerMock('gamesWin',4);
        $players[] = $this->getPlayerMock('gamesWin',9);

        shuffle($players);
        $object->sorting($players, PlayerSorting::BY_WON_GAMES);
        
        $sortby='Won Games';
        $this->makeSortingTest($players[0]->getGamesWin(), 9, $sortby);
        $this->makeSortingTest($players[1]->getGamesWin(), 4, $sortby);
        $this->makeSortingTest($players[2]->getGamesWin(), 1, $sortby);
        
    }
    
    public function testSortByFirstTieBreak()
    {
       
        $object = new PlayerSorting();
        
        $players = array();
        
        $players[] = $this->getPlayerMock('firstTiebreak',11.5);
        $players[] = $this->getPlayerMock('firstTiebreak',40);
        $players[] = $this->getPlayerMock('firstTiebreak',9);

        shuffle($players);
        $object->sorting($players, PlayerSorting::BY_FIRST_TIEBREAK);
        
        $sortby='First Tiebreak';
        $this->makeSortingTest($players[0]->getFirstTiebreak(), 40, $sortby);
        $this->makeSortingTest($players[1]->getFirstTiebreak(), 11.5, $sortby);
        $this->makeSortingTest($players[2]->getFirstTiebreak(), 9, $sortby);
        
    }
    
    public function testSortBySecondTieBreak()
    {
       
        $object = new PlayerSorting();
        
        $players = array();
        
        $players[] = $this->getPlayerMock('secondTiebreak',12.5);
        $players[] = $this->getPlayerMock('secondTiebreak',40);
        $players[] = $this->getPlayerMock('secondTiebreak',9);

        shuffle($players);
        $object->sorting($players, PlayerSorting::BY_SECOND_TIEBREAK);
        
        $sortby='Second Tiebreak';
        $this->makeSortingTest($players[0]->getSecondTiebreak(), 40, $sortby);
        $this->makeSortingTest($players[1]->getSecondTiebreak(), 12.5, $sortby);
        $this->makeSortingTest($players[2]->getSecondTiebreak(), 9, $sortby);
        
    }
    
    public function testSortByThirdTieBreak()
    {
       
        $object = new PlayerSorting();
        
        $players = array();
        
        $players[] = $this->getPlayerMock('thirdTiebreak',11.5);
        $players[] = $this->getPlayerMock('thirdTiebreak',140);
        $players[] = $this->getPlayerMock('thirdTiebreak',99.5);

        shuffle($players);
        $object->sorting($players, PlayerSorting::BY_THIRD_TIEBREAK);
        
        $sortby='Third Tiebreak';
        $this->makeSortingTest($players[0]->getThirdTiebreak(), 140, $sortby);
        $this->makeSortingTest($players[1]->getThirdTiebreak(), 99.5, $sortby);
        $this->makeSortingTest($players[2]->getThirdTiebreak(), 11.5, $sortby);
        
    }
    
    
    
    protected function makeSortingTest($value, $expected, $sortby)
    {
        $this->assertSame(
                $value, 
                $expected, 
                sprintf('Sorting by "%s" is not in expected order', $sortby)
            );
    }
        
    protected function getPlayerMock($key,$value)
    {
        
        $data = array(
            'gamesPlayed' => 3,
            'gamesSuspended' => 4,
            'gamesWin'       => 5,
            'gamesDraw'      => 6,
            'gamesLost'      => 7,
            'gamesPoints'    => 14,
            'firstTiebreak'  => 12.5,
            'secondTiebreak' => 123,
            'thirdTiebreak' => 12
        );
        
        $data[$key]=$value;
        
        $object = new Participants();
        $object->populate($data);
        
        return $object;
    }
    
    public function testMultipleSortingWithEquals()
    {
       
        $object = new PlayerSorting();
        
        $players = array();
        $dataset = $this->getDatasetForMultipleSorting();
        
        foreach($dataset as $data) {
            $players[] = $this->makeMultiplePlayerMock($data);
        }
        shuffle($players);
        
        $object->sorting($players, PlayerSorting::BY_POINTS);
        
       
        $sortby='MultipleSorting';
        for($i=0; $i<count($players); $i++) {
            $this->makeSortingTest($players[$i]->getId(), $i+1, $sortby);
        }
        
    }
    
    protected function getDatasetForMultipleSorting()
    {
        $dataset = array();
        $dataset[] = array (
            'pos' => 1,
            'points' => 20,
            'tb1' => 120.5,
            'tb2' => 12.5,
            'tb3' => 10,
            );
        
        $dataset[] = array (
            'pos' => 2,
            'points' => 18,
            'tb1' => 120.5,
            'tb2' => 12.5,
            'tb3' => 10,
            );
        
        $dataset[] = array (
            'pos' => 3,
            'points' => 18,
            'tb1' => 110,
            'tb2' => 12.5,
            'tb3' => 10,
            );
        
        $dataset[] = array (
            'pos' => 4,
            'points' => 16,
            'tb1' => 100.5,
            'tb2' => 72.5,
            'tb3' => 10,
            );
        
        $dataset[] = array (
            'pos' => 5,
            'points' => 16,
            'tb1' => 100.5,
            'tb2' => 40.5,
            'tb3' => 10,
            );
        
        $dataset[] = array (
            'pos' => 6,
            'points' => 16,
            'tb1' => 100.5,
            'tb2' => 40.5,
            'tb3' => 8,
            );
        
        $dataset[] = array (
            'pos' => 7,
            'points' => 14,
            'tb1' => 100.5,
            'tb2' => 12.5,
            'tb3' => 10,
            );
        
         $dataset[] = array (
            'pos' => 8,
            'points' => 14,
            'tb1' => 100.5,
            'tb2' => 12.5,
            'tb3' => 9,
            );
         
         $dataset[] = array (
            'pos' => 9,
            'points' => 14,
            'tb1' => 100.5,
            'tb2' => 12.5,
            'tb3' => 8,
            );
         
         return $dataset;
    }
    
    protected function makeMultiplePlayerMock($data)
    {
        
        $player = $this->getPlayerMock('gamesPoints',$data['points']);
        
        $player->setId($data['pos']);
        $player->setFirstTiebreak($data['tb1']);
        $player->setSecondTiebreak($data['tb2']);
        $player->setThirdTiebreak($data['tb3']);
        
        return $player;
        
    }
}