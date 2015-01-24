<?php
namespace NakadeTest\Rating;

use Nakade\Rating\Rating;
use User\Entity\User;
use PHPUnit_Framework_TestCase;

class RatingTest extends PHPUnit_Framework_TestCase
{
    protected $data=array();

    /**
     * data set
     */
    public function __construct() {

        $this->data=array(
            'user' => new User(),
            'rating' => 2000,
            'newRating' => 2030,
            'achievedResult' => 1.0
        );

    }

    /**
     * test initial state
     */
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

    /**
     * test properties
     */
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

    /**
     * test populating
     */
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

    /**
     * test if rating is unset
     */
    public function testHasNoRating()
    {
        $object = new Rating();
        $this->assertFalse(
            $object->hasRating(),
            sprintf("Expected result not found."
            )
        );
    }

    /**
     * test if rating is set
     */
    public function testHasRating()
    {
        $object = new Rating();
        $object->setRating(2000);

        $this->assertTrue(
            $object->hasRating(),
            sprintf("Expected result not found. Rating: '%s'", $object->getRating()
            )
        );
    }

    /**
     * test if rating is not in valid range
     */
    public function testHasInvalidRating()
    {
        $object = new Rating();
        $object->setRating(79);
        $this->assertFalse(
            $object->hasValidRating(),
            sprintf("Expected result not found. Rating: '%s'", $object->getRating()
            )
        );
    }

    /**
     * testing if rating is in a valid rang
     */
    public function testHasValidRating()
    {
        $object = new Rating();
        $object->setRating(2000);

        $this->assertTrue(
            $object->hasValidRating(),
            sprintf("Expected result not found. Rating: '%s'", $object->getRating()
            )
        );
    }

    /**
     * testing if result is unset
     */
    public function testHasNoResult()
    {
        $object = new Rating();
        $this->assertFalse(
            $object->hasResult(),
            sprintf("Expected result not found. Result: '%s'", $object->getAchievedResult()
            )
        );
    }

    /**
     * test if a result is set
     */
    public function testHasResult()
    {
        $object = new Rating();
        $object->setAchievedResult(1.0);

        $this->assertTrue(
            $object->hasResult(),
            sprintf("Expected result not found. Rating: '%s'", $object->getAchievedResult()
            )
        );
    }

    /**
     * test if rating is invalid for calculating a new rating
     */
    public function testInvalid()
    {
        $object = new Rating();

        $this->assertFalse(
            $object->isValid(),
            sprintf("Expected result not found.")
        );

        $object->setRating(2000);
        $this->assertFalse(
            $object->isValid(),
            sprintf("Expected result not found.")
        );

        $object->setRating(null);
        $object->setAchievedResult(1.0);
        $this->assertFalse(
            $object->isValid(),
            sprintf("Expected result not found.")
        );

    }

    /**
     * test if rating is valid for calculating a new rating
     */
    public function testValid()
    {
        $object = new Rating();
        $object->setRating(2000);
        $object->setAchievedResult(1.0);

        $this->assertTrue(
            $object->isValid(),
            sprintf("Expected result not found.")
        );

    }

}