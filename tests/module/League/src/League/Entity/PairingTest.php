<?php

namespace LeagueTest\Entity;

use League\Entity\Pairing;
use League\Entity\Result;
use User\Entity\User;
use PHPUnit_Framework_TestCase;

class PairingTest extends PHPUnit_Framework_TestCase
{
    public function testPairingInitialState()
    {
        $pairing = new Pairing();

        $this->assertNull(
            $pairing->getId(), 
            '"id" should initially be null'
        );
        $this->assertNull(
            $pairing->getLid(), 
            '"lid" should initially be null'
        );
        $this->assertNull(
            $pairing->getBlackId(), 
            '"blackId" should initially be null'
        );
        $this->assertNull(
            $pairing->getWhiteId(),
            '"whiteId" should initially be null'
        );
        $this->assertNull(
            $pairing->getResultId(),
            '"resultId" should initially be null'
        );
        $this->assertNull(
            $pairing->getWinnerId(), 
            '"winnerId" should initially be null'
        );
        $this->assertNull(
            $pairing->getPoints(), 
            '"points" should initially be null'
        );
        $this->assertNull(
            $pairing->getDate(), 
            '"date" should initially be null'
        );
        $this->assertNull(
            $pairing->getLeague(), 
            '"league" should initially be null'
        );
        $this->assertNull(
            $pairing->getBlack(), 
            '"black" should initially be null'
        );
        $this->assertNull(
            $pairing->getWhite(), 
            '"white" should initially be null'
        );
        $this->assertNull(
            $pairing->getWinner(), 
            '"winner" should initially be null'
        ); 
        $this->assertNull(
            $pairing->getResult(), 
            '"result" should initially be null'
        ); 
    }

    public function testSetsPropertiesCorrectly()
    {
        $pairing = new Pairing();
        
        $data = array(
            'id' => 123,
            'lid'  => 232,
            'blackId' => 334,
            'whiteId' => 5,
            'resultId' => 99,
            'winnerId' => 231,
            'points' => 44,
            'date' => new \DateTime('2000-01-01'),
            'black' => new User(),
            'white' => new User(),
            'winner' => new User(),
            'result' => new Result(),
            
        );
        

        $pairing->setId($data['id']);
        $pairing->setLid($data['lid']);
        $pairing->setBlackId($data['blackId']);
        $pairing->setWhiteId($data['whiteId']);
        $pairing->setResultId($data['resultId']);
        $pairing->setWinnerId($data['winnerId']);
        $pairing->setPoints($data['points']);      
        $pairing->setDate($data['date']);
        $pairing->setBlack($data['black']);
        $pairing->setWhite($data['white']);
        $pairing->setWinner($data['winner']);
        $pairing->setResult($data['result']);

       
        $this->assertSame(
            $data['id'], $pairing->getId(), '"id" was not set correctly'
        );
        $this->assertSame(
            $data['lid'], $pairing->getLid(), '"lid" was not set correctly'
        );
        $this->assertSame(
            $data['points'], 
            $pairing->getPoints(), 
            '"sid" was not set correctly'
        );
        $this->assertSame(
            $data['blackId'], 
            $pairing->getBlackId(), 
            '"blackId" was not set correctly'
        );
        $this->assertSame(
            $data['whiteId'], 
            $pairing->getWhiteId(), 
            '"whiteId" was not set correctly'
        );
        $this->assertSame(
            $data['resultId'], 
            $pairing->getResultId(), 
            '"resultId" was not set correctly'
        );
        $this->assertSame(
            $data['winnerId'], 
            $pairing->getWinnerId(), 
            '"winnerId" was not set correctly'
        );
        $this->assertSame(
            $data['date'], 
            $pairing->getDate(), 
            '"date" was not set correctly'
        );
        $this->assertSame(
            $data['black'], 
            $pairing->getBlack(), 
            '"black" was not set correctly'
        );
        $this->assertSame(
            $data['white'], 
            $pairing->getWhite(), 
            '"white" was not set correctly'
        );
        $this->assertSame(
            $data['winner'], 
            $pairing->getWinner(), 
            '"winner" was not set correctly'
        );
        $this->assertSame(
            $data['result'], 
            $pairing->getResult(), 
            '"result" was not set correctly'
        );
         
    }

}