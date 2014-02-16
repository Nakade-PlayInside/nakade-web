<?php
namespace League\Statistics;

use League\Statistics\Results;
use PHPUnit_Framework_TestCase;

class ResultsTest extends PHPUnit_Framework_TestCase
{
    
    public function testGetResultTypes()
    {
        $object = new Results();
        $data = $object->getResultTypes();
        
        $this->assertInternalType(
                'array', 
                $data, 
                sprintf('"%s" should return an array', 'getResultTypes()')
        );
        
        foreach($data as $value){
          
            $this->assertInternalType(
                'string', 
                $value, 
                sprintf('"%s" should be a string', $value)
            );
            
        } 
    }
    
    public function testGetResult()
    {
         $object = new Results();
         
         $this->assertNull(
                $object->getResult(100), 
                sprintf('"%s" should return null', 'getResult()')
         );
         
         for($i=1; $i<count($object->getResultTypes()); $i++) {
         
            $this->assertInternalType(
                   'string', 
                   $object->getResult($i), 
                   sprintf('"getResult(%i)" should return a string', $i)
            );
         
         }
    }
    
}