<?php
namespace Message\Services;

use Nakade\Abstracts\AbstractFormFactory;
use Message\Form\MessageForm;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MessageFormService
 *
 * @package Message\Services
 */
class MessageFormService extends AbstractFormFactory
{
    const MESSAGE_FORM = 'message';

    private $services;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $this->services = $services;
        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['Message']['text_domain']) ?
            $config['Message']['text_domain'] : null;

        $translator = $services->get('translator');
        $this->setTranslator($translator, $textDomain);

        return $this;
    }

    /**
     * @param string $typ
     *
     * @return \Zend\Form\Form
     *
     * @throws \RuntimeException
     */
    public function getForm($typ)
    {
        switch (strtolower($typ)) {

            case self::MESSAGE_FORM:
                $form = new MessageForm($this->getServices());
                break;

            default:
                throw new \RuntimeException(
                    sprintf('An unknown form type was provided.')
                );
        }

        $form->setTranslator($this->translator, $this->textDomain);
        return $form;
    }


    /**
     * @return ServiceLocatorInterface
     */
    public function getServices()
    {
        return $this->services;
    }


}
