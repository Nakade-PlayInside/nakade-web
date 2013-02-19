<?php

namespace LeagueTest\Entity;

use League\Entity\Season;
use PHPUnit_Framework_TestCase;

class SeasonTest extends PHPUnit_Framework_TestCase
{
    public function testSeasonInitialState()
    {
        $saison = new Season();

        $this->assertNull(
            $saison->getId(), 
            '"id" should initially be null'
        );
        $this->assertNull(
            $saison->getTitle(), 
            '"title" should initially be null'
        );
        $this->assertNull(
            $saison->getAbbreviation(), 
            '"abbreviation" should initially be null'
        );
       
        $this->assertNull(
            $saison->geActive(), 
            '"Active" should initially be null'
        );
        $this->assertNull(
            $saison->getYear(), 
            '"Year" should initially be null'
        );
         $this->assertNull(
            $saison->getNumber(), 
            '"Number" should initially be null'
        );
       
    }

    public function testSetsPropertiesCorrectly()
    {
        $saison = new Season();
        
        $data = array(
            'id' => 123,
            'title'  => 'sieg',
            'abbreviation'  => 'sed',
            'active' => TRUE,
            'year'  => new DateTime(),
            'number'  => 3,
        );

        $saison->setId($data['id']);
        $saison->setTitle($data['title']);
        $saison->setAbbreviation($data['abbreviation']);
        $saison->setActive($data['active']);
        $saison->setYear($data['year']);
        $saison->setNumber($data['number']);
        
        $this->assertSame(
            $data['id'], $saison->getId(), '"id" was not set correctly'
        );
        $this->assertSame(
            $data['title'], 
            $saison->getTitle(), 
            '"title" was not set correctly'
        );
        $this->assertSame(
            $data['abbreviation'], 
            $saison->getAbbreviation(), 
            '"abbreviation" was not set correctly'
        );
        $this->assertSame(
            $data['active'], 
            $saison->setActive(), 
            '"active" was not set correctly'
        );
        $this->assertSame(
            $data['year'], 
            $saison->setYear(), 
            '"year" was not set correctly'
        );
        $this->assertSame(
            $data['number'], 
            $saison->setNumber(), 
            '"number" was not set correctly'
        );
        
         
    }

}