<?php
namespace NakadeTest\Rating;

use Nakade\Rating\Rating;
use User\Entity\User;
use PHPUnit_Framework_TestCase;

class RatingTest extends PHPUnit_Framework_TestCase
{
    protected $data=array();

    public function __construct() {

        $this->data=array(
            'user' => new User(),
            'rating' => 2000,
            'newRating' => 2030,
            'achievedResult' => 1.0
        );

    }


    public function testInitialState()
    {
        $object = new Rating();

        $keys = array_keys($this->data);
        foreach($keys as $property) {

            $method = 'get'.ucfirst($property);
            if (method_exists($this, $method)) {
                $this->assertNull(
                    $object->$method(),
                    sprintf('"%s" should initially be null', $property)
                );
            }
        }

    }

    public function testSetsPropertiesCorrectly()
    {
        $object = new Rating();

        foreach($this->data as $property => $value) {

            $method = 'set'.ucfirst($property);
            $object->$method($value);

            $get = 'get'.ucfirst($property);

            $this->assertSame(
                    $object->$get(),
                    $value,
                    sprintf('"%s" was not set correctly', $property)
                );
            }

    }

    public function testPopulate()
    {
        $object = new Rating();
        $object->populate($this->data);

        foreach($this->data as $property => $value) {

            $method = 'get'.ucfirst($property);

            $this->assertSame(
                $object->$method(),
                $value,
                sprintf('"%s" was not set correctly', $property)
            );
        }

    }

}