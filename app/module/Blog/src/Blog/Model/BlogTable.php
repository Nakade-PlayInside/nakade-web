<?php
namespace Blog\Model;

use Zend\Db\TableGateway\TableGateway;

class BlogTable
{
    protected $_tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->_tableGateway = $tableGateway;
    }
    
    public function fetchAll()
    {
        $select = $this->_tableGateway->getSql()->select();
        $select->where(array('post_type' => 'post'))->where(
            array(
                'post_status' => 'publish'
            )
        );
        $select->order('id DESC')->limit(3);
        $resultSet = $this->_tableGateway->selectWith($select);
       // $resultSet = $this->tableGateway->select(array
       // ('post_type' => 'post', 'post_status' => 'publish'));
        return $resultSet;
    }

    public function getPost($pid)
    {
        $pid  = (int) $pid;
        $rowset = $this->_tableGateway->select(array('id' => $pid));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $pid");
        }
        return $row;
    }
    
}
