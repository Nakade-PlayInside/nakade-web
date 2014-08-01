<?php
namespace User\Services;

use User\Form\Factory\UserFilterFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserFilterService
 *
 * @package User\Services
 */
class UserFilterService implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return UserFilterService
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {

        /* @var $entityManager \Doctrine\ORM\EntityManager */
        $entityManager = $services->get('Doctrine\ORM\EntityManager');

        if (is_null($entityManager)) {
            throw new \RuntimeException(
                sprintf('Entity manager could not be found in service.')
            );
        }

        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['User']['text_domain']) ?
            $config['User']['text_domain'] : null;

        /* @var $translator \Zend\I18n\Translator\Translator; */
        $translator = $services->get('translator');

        $factory = new UserFilterFactory($entityManager);
        $factory->setTranslator($translator, $textDomain);

        return $factory;
    }

}
