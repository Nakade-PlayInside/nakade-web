<?php

namespace LeagueTest\Helper;

use League\Helper\PositionCalculator;
use PHPUnit_Framework_TestCase;
use League\Entity\Table;

class PositionCalculatorTest extends PHPUnit_Framework_TestCase
{
    

    public function testSetsPropertiesCorrectly()
    {
        
        $data = array(
            'result' => 12,
            'winner'  => 5,
            'points' => 334,
            
        );
        
        $calc = new PositionCalculator($data);
        
        
        $this->assertSame(
            $data['result'], $calc->getResultId(), 
                '"resultId" was not set correctly'
        );
        $this->assertSame(
            $data['winner'], $calc->getWinnerId(), 
                '"winnerId" was not set correctly'
        );
        $this->assertSame(
            $data['points'], $calc->getPoints(), 
            '"points" was not set correctly'
        );
         
    }
    
    public function testNormalizingPoints()
    {
        
        $data = array(
            'points' => 334
        );
        
        $calc = new PositionCalculator($data);
        
        
        $calc->nomalize();
        
        $this->assertSame(
            PositionCalculator::MAX_POINTS, $calc->getPoints(), 
            '"points" was not normalized correctly'
        );
         
    }

    
    public function testCaculatingGamesPlayed()
    {
        
        $results = array (1,2,3,4);
        
        foreach ($results as $resultId){
            
            $calc = new PositionCalculator(array('result' => $resultId));
            $data = $calc->getData();
            
            $this->assertSame(
            1, $data['gamesPlayed'], 
            '"gamesPlayed" was not calculated correctly: resuldId:'.$resultId
            );
            
        }
    }
    
    public function testCaculatingSuspendedGames()
    {
        
            $calc = new PositionCalculator(array('result' => 5));
            $data = $calc->getData();
            
            $this->assertSame(
            1, $data['gamesSuspended'], 
            '"gamesSuspended" was not calculated correctly'
            );
       
    }
    
    public function testCaculatingJigo()
    {
        
            $calc = new PositionCalculator(array('result' => 3));
            $data = $calc->getData();
            
            $this->assertSame(
            1, $data['jigo'], 
            '"jigo" was not calculated correctly'
            );
       
    }
    
    public function testCaculatingWin()
    {
        
        $results = array (1,2,4);
        
        foreach ($results as $resultId){
            
            $winnerId = 1;
            $entity = new Table();
            $entity->setUid($winnerId);
            $calc = new PositionCalculator(
                    array(
                        'result' => $resultId,
                        'winner' => $winnerId,      
                    )
            );
            $calc->bindEntity($entity);
            $data = $calc->getData();
            
            $this->assertSame(
            1, $data['win'], 
            '"win" was not calculated correctly: resuldId:'.$resultId
            );
            
        }
       
    }
    
    public function testCaculatingLoss()
    {
        
        $results = array (1,2,4);
        
        foreach ($results as $resultId){
            
            $winnerId = 1;
            $entity = new Table();
            $entity->setUid(2);
            $calc = new PositionCalculator(
                    array(
                        'result' => $resultId,
                        'winner' => $winnerId,      
                    )
            );
            $calc->bindEntity($entity);
            $data = $calc->getData();
            
            $this->assertSame(
            1, $data['loss'], 
            '"loss" was not calculated correctly: resuldId:'.$resultId
            );
            
        }
       
    }
    
    public function testCaculatingPointsWinByResignation()
    {
        
            $winnerId = 1;
            $entity = new Table();
            $entity->setUid($winnerId);
            $calc = new PositionCalculator(
                    array(
                        'result' => 1,
                        'winner' => $winnerId,      
                    )
            );
            $calc->bindEntity($entity);
            $data = $calc->getData();
            
            $this->assertSame(
            2*PositionCalculator::MAX_POINTS, $data['tiebreaker1'], 
            '"tiebreaker1" was not calculated correctly'
            );
            
        
       
    }
    
    public function testCaculatingPointsWinByPoints()
    {
            
            $myData = array(
                'result' => 2,
                'winner' => 1,
                'points' => 5.5,
            );
        
           
            $entity = new Table();
            $entity->setUid($myData['winner']);
            $calc = new PositionCalculator($myData);
            
            $calc->bindEntity($entity);
            $data = $calc->getData();
            
            $this->assertSame(
            PositionCalculator::MAX_POINTS + $myData['points'],
                $data['tiebreaker1'], 
                '"tiebreaker1" was not calculated correctly'
            );
            
        
       
    }
    
    public function testCaculatingPointsLossByPoints()
    {
            
            $myData = array(
                'result' => 2,
                'winner' => 1,
                'points' => 5.5,
            );
        
           
            $entity = new Table();
            $entity->setUid(2);
            $calc = new PositionCalculator($myData);
            
            $calc->bindEntity($entity);
            $data = $calc->getData();
            
            $this->assertSame(
            PositionCalculator::MAX_POINTS - $myData['points'],
                $data['tiebreaker1'], 
                '"tiebreaker1" was not calculated correctly'
            );
            
        
       
    }




}