<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Entity Class representing a blog post
 *
 * @ORM\Entity
 * @ORM\Table(name="wp_posts")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="post_author", type="integer")
     */
    private $author;

    /**
     * @ORM\Column(name="post_date", type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(name="post_content", type="text")
     */
    private $content;

    /**
     * @ORM\Column(name="post_title", type="string")
     */
    private $title;


    /**
     * @ORM\Column(name="post_status", type="string")
     */
    private $status; //publish


    /**
     * @ORM\Column(name="post_type", type="string")
     */
    private $type;

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}