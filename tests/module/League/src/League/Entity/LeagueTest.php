<?php

namespace LeagueTest\Entity;

use League\Entity\League;
use PHPUnit_Framework_TestCase;

class LeagueTest extends PHPUnit_Framework_TestCase
{
    public function testLeagueInitialState()
    {
        $league = new League();

        $this->assertNull(
            $league->getId(), 
            '"id" should initially be null'
        );
        $this->assertNull(
            $league->getSid(), 
            '"sid" should initially be null'
        );
        $this->assertNull(
            $league->getOrder(), 
            '"order" should initially be null'
        );
        $this->assertNull(
            $league->getDivision(),
            '"division" should initially be null'
        );
        $this->assertNull(
            $league->getTitle(),
            '"title" should initially be null'
        );
        $this->assertNull(
            $league->getRuleId(), 
            '"ruleId" should initially be null'
        );
        $this->assertNull(
            $league->getSeason(), 
            '"season" should initially be null'
        );
    }

    public function testSetsPropertiesCorrectly()
    {
        $league = new League();
        
        $data = array(
            'id' => 123,
            'title'  => 'some title',
            'sid' => 334,
            'order' => 5,
            'division' => 'Seria B',
            'ruleId' => 231,
            'season' => new \League\Entity\Season(),
            
        );
        

        $league->setId($data['id']);
        $league->setTitle($data['title']);
        $league->setSid($data['sid']);
        $league->setOrder($data['order']);
        $league->setDivision($data['division']);
        $league->setRuleId($data['ruleId']);
        $league->setSeason($data['season']);

       
        $this->assertSame(
            $data['id'], $league->getId(), '"id" was not set correctly'
        );
        $this->assertSame(
            $data['title'], $league->getTitle(), '"title" was not set correctly'
        );
        $this->assertSame(
            $data['sid'], 
            $league->getSid(), 
            '"sid" was not set correctly'
        );
        $this->assertSame(
            $data['order'], 
            $league->getOrder(), 
            '"order" was not set correctly'
        );
        $this->assertSame(
            $data['division'], 
            $league->getDivision(), 
            '"division" was not set correctly'
        );
        $this->assertSame(
            $data['ruleId'], 
            $league->getRuleId(), 
            '"ruleId" was not set correctly'
        );
        $this->assertSame(
            $data['season'], 
            $league->getSeason(), 
            '"season" was not set correctly'
        );
         
    }

}