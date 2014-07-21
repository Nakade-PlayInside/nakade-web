<?php
namespace User\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Form\Factory\UserFieldFactory;

/**
 * Class UserFieldService
 *
 * @package User\Services
 */
class UserFieldService implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return UserFieldFactory
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['User']['text_domain']) ?
            $config['User']['text_domain'] : null;

        /* @var $translator \Zend\I18n\Translator\Translator; */
        $translator = $services->get('translator');

        $factory = new UserFieldFactory();
        $factory->setTranslator($translator, $textDomain);

        return $factory;
    }

}
