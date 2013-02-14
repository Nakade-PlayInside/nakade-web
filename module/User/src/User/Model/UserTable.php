<?php
namespace User\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable
{
    protected $_tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->_tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->_tableGateway->select();
        return $resultSet;
    }

    public function getUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->_tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveUser(User $user)
    {
        $data = array(
            'artist' => $user->artist,
            'title'  => $user->title,
        );

        $id = (int)$user->id;
        if ($id == 0) {
            $this->_tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $this->_tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteUser($id)
    {
        $this->_tableGateway->delete(array('id' => $id));
    }
}