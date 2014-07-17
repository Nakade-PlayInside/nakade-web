<?php
namespace Blog\Controller;

use Blog\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractController
{

    /**
     * @return array|ViewModel
     */
   public function indexAction()
   {
        return new ViewModel(array(
               'blog' => $this->getBlogMapper()->getLatestPosts(),
            )
        );
   }

   /**
    * @return \Blog\Services\RepositoryService
    */
   public function getRepository()
   {
        return $this->repository;
   }

   /**
    * @return \Blog\Mapper\BlogMapper
    */
   public function getBlogMapper()
   {
       return $this->getRepository()->getMapper(RepositoryService::BLOG_MAPPER);
   }

}