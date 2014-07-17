<?php
namespace Blog\Paginator;

use Blog\Mapper\BlogMapper;

class CarouselPaging
{
   private $posts;
   private $page;
   private $mapper;

   /**
    * @param BlogMapper $mapper
    * @param int        $page
    */
   public function __construct(BlogMapper $mapper, $page)
   {

       $this->mapper = $mapper;
       $this->posts = $this->getMapper()->getLatestPosts();
       $this->page = $page;
       if ($page > count($this->posts)) {
           $this->page = 1;
       }
   }

   /**
    * @return mixed
    */
   public function getActualPost()
   {
       $page = $this->page-1;
       return $this->posts[$page];
   }

   /**
    * @return int
    */
   public function getNext()
   {
       $next = $this->page+1;
       if ($this->page >= count($this->posts)) {
           $next = 1;
       }
       return $next;
   }

    /**
     * @return int
     */
    public function getPrevious()
    {
        $prev = count($this->posts);
        if ($this->page > 1) {
            $prev = $this->page-1;
        }
        return $prev;
    }

    /**
     * @return \Blog\Mapper\BlogMapper
     */
    private function getMapper()
    {
        return $this->mapper;
    }


}