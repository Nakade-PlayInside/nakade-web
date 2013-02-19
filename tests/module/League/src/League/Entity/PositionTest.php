<?php

namespace LeagueTest\Entity;

use League\Entity\Position;
use League\Entity\League;
use User\Entity\User;

use PHPUnit_Framework_TestCase;

class PositionTest extends PHPUnit_Framework_TestCase
{
    public function testPositionInitialState()
    {
        $position = new Position();

        $this->assertNull(
            $position->getId(), 
            '"id" should initially be null'
        );
        $this->assertNull(
            $position->getLid(), 
            '"lid" should initially be null'
        );
        $this->assertNull(
            $position->getUid(), 
            '"Uid" should initially be null'
        );
        $this->assertNull(
            $position->getGamesPlayed(),
            '"gamesPlayed" should initially be null'
        );
        $this->assertNull(
            $position->getWin(),
            '"Win" should initially be null'
        );
        $this->assertNull(
            $position->getLoss(), 
            '"Loss" should initially be null'
        );
        $this->assertNull(
            $position->getJigo(), 
            '"Jigo" should initially be null'
        );
        $this->assertNull(
            $position->getSuspendedGames(), 
            '"suspendedGames" should initially be null'
        );
        $this->assertNull(
            $position->getTiebreaker1(), 
            '"tiebreaker1" should initially be null'
        );
         $this->assertNull(
            $position->getTiebreaker2(), 
            '"tiebreaker2" should initially be null'
        );
        $this->assertNull(
            $position->getLeague(), 
            '"League" should initially be null'
        );
         $this->assertNull(
            $position->getPlayer(), 
            '"Player" should initially be null'
        ); 
       
    }

    public function testSetsPropertiesCorrectly()
    {
        $position = new Position();
        
        $data = array(
            'id' => 123,
            'lid'  => 232,
            'uid' => 334,
            'GP' => 5,
            'W' => 99,
            'L' => 231,
            'J' => 44,
            'GS' => 3,
            'tb1' => 10.5,
            'tb2' => 4.5,
            'player' => new User(),
            'league' => new League(),
            
        );
        

        $position->setId($data['id']);
        $position->setLid($data['lid']);
        $position->setUid($data['uid']);
        $position->setGamesPlayed($data['GP']);
        $position->setWin($data['W']);
        $position->setLoss($data['L']);
        $position->setJigo($data['J']);      
        $position->setSuspendedGames($data['GS']);
        $position->setTiebreaker1($data['tb1']);
        $position->setTiebreaker2($data['tb2']);
        $position->setLeague($data['league']);
        $position->setPlayer($data['player']);
        

       
        $this->assertSame(
            $data['id'], $position->getId(), '"id" was not set correctly'
        );
        $this->assertSame(
            $data['lid'], $position->getLid(), '"lid" was not set correctly'
        );
        $this->assertSame(
            $data['uid'], 
            $position->getUid(), 
            '"uid" was not set correctly'
        );
        $this->assertSame(
            $data['GP'], 
            $position->getGamesPlayed(), 
            '"GamesPlayed" was not set correctly'
        );
        $this->assertSame(
            $data['W'], 
            $position->getWin(), 
            '"Win" was not set correctly'
        );
        $this->assertSame(
            $data['L'], 
            $position->getLoss(), 
            '"Loss" was not set correctly'
        );
        $this->assertSame(
            $data['J'], 
            $position->getJigo(), 
            '"Jigo" was not set correctly'
        );
        $this->assertSame(
            $data['GS'], 
            $position->getSuspendedGames(), 
            '"SuspendedGames" was not set correctly'
        );
        $this->assertSame(
            $data['tb1'], 
            $position->getTiebreaker1(), 
            '"Tiebreaker1" was not set correctly'
        );
        $this->assertSame(
            $data['tb2'], 
            $position->getTiebreaker2(), 
            '"Tiebreaker2" was not set correctly'
        );
        $this->assertSame(
            $data['league'], 
            $position->getLeague(), 
            '"League" was not set correctly'
        );
        $this->assertSame(
            $data['player'], 
            $position->getPlayer(), 
            '"Player" was not set correctly'
        );
         
    }

}