<?php

namespace UserTest\Model;

use User\Model\User;
use PHPUnit_Framework_TestCase;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testUserInitialState()
    {
        $user = new User();

        $this->assertNull($user->artist, '"artist" should initially be null');
        $this->assertNull($user->id, '"id" should initially be null');
        $this->assertNull($user->title, '"title" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $user = new User();
        $data  = array('artist' => 'some artist',
                       'id'     => 123,
                       'title'  => 'some title');

        $user->exchangeArray($data);

        $this->assertSame(
            $data['artist'], 
            $user->artist, 
            '"artist" was not set correctly'
        );
        $this->assertSame(
            $data['id'], $user->id, '"id" was not set correctly'
        );
        $this->assertSame(
            $data['title'], $user->title, '"title" was not set correctly'
        );
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $user = new User();

        $user->exchangeArray(
            array('artist' => 'some artist',
                  'id'     => 123,
                  'title'  => 'some title')
        );
        $user->exchangeArray(array());

        $this->assertNull(
            $user->artist, 
            '"artist" should have defaulted to null'
        );
        $this->assertNull(
            $user->id, '"id" should have defaulted to null'
        );
        $this->assertNull(
            $user->title, 
            '"title" should have defaulted to null'
        );
    }
}