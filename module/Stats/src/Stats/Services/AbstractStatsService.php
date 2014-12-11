<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AchievementService
 *
 * @package Stats\Services
 */
abstract class AbstractStatsService implements FactoryInterface
{
    protected $repository;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Zend\ServiceManager\FactoryInterface
     *
     * @throws \RuntimeException
     */
    abstract public function createService(ServiceLocatorInterface $services);

    /**
     * @return \Stats\Services\RepositoryService
     */
    public function  getRepository()
    {
        return $this->repository;
    }

    /**
     * @return \Stats\Mapper\StatsMapper
     */
    public function getMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::STATS_MAPPER);
    }



}