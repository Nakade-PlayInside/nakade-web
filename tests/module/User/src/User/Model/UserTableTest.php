<?php
namespace UserTest\Model;

use User\Model\UserTable;
use User\Model\User;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class UserTableTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllReturnsAllUsers()
    {
        $resultSet        = new ResultSet();
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'), array(), '', false
        );
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $userTable = new UserTable($mockTableGateway);

        $this->assertSame($resultSet, $userTable->fetchAll());
    }
    
    public function testCanRetrieveAnUserByItsId()
    {
    $user = new User();
    $user->exchangeArray(
        array('id'     => 123,
                                'artist' => 'The Military Wives',
                                'title'  => 'In My Dreams')
    );

    $resultSet = new ResultSet();
    $resultSet->setArrayObjectPrototype(new User());
    $resultSet->initialize(array($user));

    $mockTableGateway = $this->getMock(
        'Zend\Db\TableGateway\TableGateway', 
        array('select'), array(), '', false
    );
    $mockTableGateway->expects($this->once())
                     ->method('select')
                     ->with(array('id' => 123))
                     ->will($this->returnValue($resultSet));

    $userTable = new UserTable($mockTableGateway);

    $this->assertSame($user, $userTable->getUser(123));
    }

public function testCanDeleteAnUserByItsId()
{
    $mockTableGateway = $this->getMock(
        'Zend\Db\TableGateway\TableGateway', 
        array('delete'), array(), '', false
    );
    $mockTableGateway->expects($this->once())
                     ->method('delete')
                     ->with(array('id' => 123));

    $userTable = new UserTable($mockTableGateway);
    $userTable->deleteUser(123);
}

public function testSaveAlbumWillInsertNewUsersIfTheyDontAlreadyHaveAnId()
{
    $userData = array(
        'artist' => 'The Military Wives', 'title' => 'In My Dreams');
    $user     = new User();
    $user->exchangeArray($userData);

    $mockTableGateway = $this->getMock(
        'Zend\Db\TableGateway\TableGateway', 
        array('insert'), array(), '', false
    );
    $mockTableGateway->expects($this->once())
                     ->method('insert')
                     ->with($userData);

    $userTable = new UserTable($mockTableGateway);
    $userTable->saveUser($user);
}

public function testSaveUserWillUpdateExistingUsersIfTheyAlreadyHaveAnId()
{
    $userData = array(
        'id' => 123, 
        'artist' => 'The Military Wives', 
        'title' => 'In My Dreams'
    );
    $user     = new User();
    $user->exchangeArray($userData);

    $resultSet = new ResultSet();
    $resultSet->setArrayObjectPrototype(new User());
    $resultSet->initialize(array($user));

    $mockTableGateway = $this->getMock(
        'Zend\Db\TableGateway\TableGateway',
        array('select', 'update'), array(), '', false
    );
    $mockTableGateway->expects($this->once())
             ->method('select')
             ->with(array('id' => 123))
             ->will(
                 $this->returnValue($resultSet)
             );
    $mockTableGateway->expects($this->once())
                     ->method('update')
                     ->with(
                         array('artist' => 'The Military Wives', 
                               'title' => 'In My Dreams'),
                         array('id' => 123)
                     );

    $userTable = new UserTable($mockTableGateway);
    $userTable->saveUser($user);
}

public function testExceptionIsThrownWhenGettingNonexistentUser()
{
    $resultSet = new ResultSet();
    $resultSet->setArrayObjectPrototype(new User());
    $resultSet->initialize(array());

    $mockTableGateway = $this->getMock(
        'Zend\Db\TableGateway\TableGateway', 
        array('select'), array(), '', false
    );
    $mockTableGateway->expects($this->once())
                     ->method('select')
                     ->with(array('id' => 123))
                     ->will($this->returnValue($resultSet));

    $userTable = new UserTable($mockTableGateway);

    try
    {
        $userTable->getUser(123);
    }
    catch (\Exception $e)
    {
        $this->assertSame('Could not find row 123', $e->getMessage());
        return;
    }

    $this->fail('Expected exception was not thrown');
}
}