<?php

namespace LeagueTest\Entity;

use League\Entity\Result;
use PHPUnit_Framework_TestCase;

class ResultTest extends PHPUnit_Framework_TestCase
{
    public function testResultInitialState()
    {
        $result = new Result();

        $this->assertNull(
            $result->getId(), 
            '"id" should initially be null'
        );
        $this->assertNull(
            $result->getResult(), 
            '"result" should initially be null'
        );
       
       
    }

    public function testSetsPropertiesCorrectly()
    {
        $result = new Result();
        
        $data = array(
            'id' => 123,
            'result'  => 'sieg',
        );

        $result->setId($data['id']);
        $result->setResult($data['result']);
       
        
        $this->assertSame(
            $data['id'], $result->getId(), '"id" was not set correctly'
        );
        $this->assertSame(
            $data['result'], 
            $result->getResult(), 
            '"result" was not set correctly'
        );
        
         
    }

}