<?php
namespace Application\Model;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Blog
{
    public $id;
    public $author;
    public $date;
    public $content;
    public $title;
    public $post_status; //publish
    public $post_type; //post
    
    
    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->author = (isset($data['post_author'])) ? $data['post_author'] : null;
        $this->title  = (isset($data['post_title'])) ? $data['post_title'] : null;
        $this->date     = (isset($data['post_date'])) ? $data['post_date'] : null;
        $this->content = (isset($data['post_content'])) ? $data['post_content'] : null;
        $this->title  = (isset($data['post_title'])) ? $data['post_title'] : null;
    }
    
    
}
?>
