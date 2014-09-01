<?php
namespace Authentication\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Authentication\Adapter\AuthAdapter;
/**
 * Class AuthAdapterService
 *
 * @package Authentication\Services
 */
class AuthAdapterService implements FactoryInterface
{


    /**
     * @param ServiceLocatorInterface $services
     *
     * @return AuthAdapter|mixed
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['NakadeAuth']['text_domain']) ?
            $config['NakadeAuth']['text_domain'] : null;
        $translator = $services->get('translator');

        //PasswordService
        if (!$services->has('Nakade\Services\PasswordService')) {
            throw new \RuntimeException('PasswordService not found.');
        }
        $pwdService = $services->get('Nakade\Services\PasswordService');
        $authOptions = $services->get('Authentication\Services\AuthOptionsService');

        //creating authentication and storage adapter
        $adapter = new AuthAdapter($pwdService, $authOptions);
        $adapter->setTranslator($translator);
        $adapter->setTextDomain($textDomain);

        return $adapter;

    }

}


