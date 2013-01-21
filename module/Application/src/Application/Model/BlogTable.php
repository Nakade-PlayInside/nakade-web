<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class BlogTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function fetchAll()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('post_type' => 'post'))->where(array('post_status' => 'publish'));
        $select->order('id DESC')->limit(2);
        $resultSet = $this->tableGateway->selectWith($select);
       // $resultSet = $this->tableGateway->select(array('post_type' => 'post', 'post_status' => 'publish'));
        return $resultSet;
    }

    public function getPost($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
}

?>
