<?php

namespace UserTest\Entity;

use User\Entity\User;
use PHPUnit_Framework_TestCase;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testUserInitialState()
    {
        $user = new User();

        $this->assertNull(
            $user->getFirstName(), 
            '"firstname" should initially be null'
        );
        $this->assertNull(
            $user->getLastName(), 
            '"lastname" should initially be null'
        );
        $this->assertNull(
            $user->getNickName(), 
            '"nickname" should initially be null'
        );
        $this->assertNull(
            $user->getId(),
            '"id" should initially be null'
        );
        $this->assertNull(
            $user->getTitle(),
            '"title" should initially be null'
        );
        $this->assertNull(
            $user->getSex(), 
            '"sex" should initially be null'
        );
        $this->assertNull(
            $user->getBirthday(), 
            '"birthday" should initially be null'
        );
        $this->assertNull(
            $user->isAnonym(), 
            '"anonym" should initially be null'
        );
    }

    public function testSetsPropertiesCorrectly()
    {
        $user = new User();
        
        $data = array(
            'id' => 123,
            'title'  => 'some title',
            'firstname' => 'Hans Otto',
            'lastname' => 'Maier',
            'nickname' => 'Whopper',
            'sex' => 'f',
            'birthday' => new \DateTime("now"),
            'anonym' => TRUE,
            
        );
        

        $user->setId($data['id']);
        $user->setTitle($data['title']);
        $user->setFirstName($data['firstname']);
        $user->setLastName($data['lastname']);
        $user->setNickName($data['nickname']);
        $user->setSex($data['sex']);
        $user->setBirthday($data['birthday']);
        $user->setAnonym($data['anonym']);

       
        $this->assertSame(
            $data['id'], $user->getId(), '"id" was not set correctly'
        );
        $this->assertSame(
            $data['title'], $user->getTitle(), '"title" was not set correctly'
        );
        $this->assertSame(
            $data['firstname'], 
            $user->getFirstName(), 
            '"First Name" was not set correctly'
        );
        $this->assertSame(
            $data['lastname'], 
            $user->getLastName(), 
            '"Last Name" was not set correctly'
        );
        $this->assertSame(
            $data['nickname'], 
            $user->getNickName(), 
            '"Nick Name" was not set correctly'
        );
        $this->assertSame(
            $data['sex'], 
            $user->getSex(), 
            '"Sex" was not set correctly'
        );
        $this->assertSame(
            $data['birthday'], 
            $user->getBirthday(), 
            '"Birthday" was not set correctly'
        );
        $this->assertSame(
            $data['anonym'], 
            $user->isAnonym(), 
            '"Anonym" was not set correctly'
        );
         
    }

}