<?php
namespace Authentication\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Authentication\Adapter\AuthAdapter;
use Authentication\Adapter\AuthStorage;
use Zend\Authentication\AuthenticationService;
use DoctrineModule\Options\Authentication as AuthOptions;

/**
 * Factory for creating the Zend Authentication Services. Using customized
 * Adapter and Storage instances.
 *
 * Class AuthServiceFactory
 *
 * @package Authentication\Services
 */
class AuthServiceFactory implements FactoryInterface
{


    /**
     * Creating Zend Authentication Services for logIn and logOut action.
     * Making use of customized adapters for more action as by default.
     * Integration of optional translation feature (i18N)
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return \Zend\Authentication\AuthenticationService
     *
     * @throws \RuntimeException
     *
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config  = $services->get('config');

        //configuration options as set in module.config
        $options = $config['doctrine']['authentication']['orm_default'] ?
            $config['doctrine']['authentication']['orm_default'] : null;

        if (is_null($options)) {
            throw new \RuntimeException('ORM default options are not found.');
        }

        //text domain
        $textDomain = isset($config['NakadeAuth']['text_domain']) ?
            $config['NakadeAuth']['text_domain'] : null;

        //lifeTime
        $lifeTimeInDays = isset($config['NakadeAuth']['cookie_life_time']) ?
            $config['NakadeAuth']['cookie_life_time'] : 14;
        $lifeTime = $this->getLifeTimeInSeconds($lifeTimeInDays);

        //EntityManager for database access by doctrine
        if (!$services->has('Doctrine\ORM\EntityManager')) {
            throw new \RuntimeException('Entity manager is not found.');
        }
        $entityManager = $services->get('Doctrine\ORM\EntityManager');
        $translator = $services->get('translator');


        //auth Options instance and setting the entityManager
        $authOptions = new AuthOptions($options);
        $authOptions->setObjectManager($entityManager);

        //creating authentication and storage adapter
        $adapter = new AuthAdapter($authOptions);
        $storage = new AuthStorage($authOptions);
        $storage->setCookieLifeTime($lifeTime);

        //set translator
        $adapter->setTranslator($translator);
        $adapter->setTextDomain($textDomain);

        //Zend Authentication Services
        return new AuthenticationService($storage, $adapter);

    }

    /**
     * @param int $lifeTimeInDays
     *
     * @return int
     */
    private function getLifeTimeInSeconds($lifeTimeInDays)
    {
        return intval($lifeTimeInDays)*24*60*60;
    }

}


