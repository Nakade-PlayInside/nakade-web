<?php

namespace Blog\Services;

use Blog\Paginator\CarouselPaging;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * Class CarouselPostService
 *
 * @package Blog\Services
 */
class CarouselPostService implements FactoryInterface
{
    private $repository;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Zend\ServiceManager\FactoryInterface
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $this->repository = $services->get('Blog\Services\RepositoryService');
        return $this;
    }

    /**
     * @param int $page
     *
     * @return CarouselPaging
     */
    public function getCarouselPaging($page)
    {
        $mapper = $this->getRepository()->getMapper(RepositoryService::BLOG_MAPPER);
        return new CarouselPaging($mapper, $page);
    }

    /**
     * @return \Blog\Services\RepositoryService
     */
    private function getRepository()
    {
        return $this->repository;
    }

}
