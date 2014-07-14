<?php

namespace Season\Services;

use Season\Schedule\ScheduleMail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SeasonService
 *
 * @package Season\Services
 */
class SeasonService implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Season\Schedule\ScheduleMail
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $repository \Season\Services\RepositoryService */
        $repository = $services->get('Season\Services\RepositoryService');

        if (is_null($repository)) {
            throw new \RuntimeException(
                sprintf('Repository service could not be found.')
            );
        }

        /* @var $mail \Season\Services\MailService */
        $mail = $services->get('Season\Services\MailService');
        return new ScheduleMail($repository, $mail);
    }

}
