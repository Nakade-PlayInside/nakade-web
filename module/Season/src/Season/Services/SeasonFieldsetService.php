<?php

namespace Season\Services;

use Nakade\Abstracts\AbstractFieldsetFactory;
use Season\Form\Fieldset;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SeasonFieldsetService
 *
 * @package Season\Services
 */
class SeasonFieldsetService extends AbstractFieldsetFactory
{

    const TIEBREAKER_FIELD_SET = 'tiebreaker';
    const BUTTON_FIELD_SET = 'button';
    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');

        //configuration
        $textDomain = isset($config['Season']['text_domain']) ?
            $config['Season']['text_domain'] : null;

        $translator = $services->get('translator');
        $repository = $services->get('Season\Services\RepositoryService');
        $this->setRepository($repository);
        $this->setTranslator($translator);
        $this->setTranslatorTextDomain($textDomain);

        return $this;
    }

    /**
     * @param string $typ
     *
     * @return \Zend\Form\Fieldset
     *
     * @throws \RuntimeException
     */
    public function getFieldset($typ)
    {
        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');

        switch (strtolower($typ)) {

            case self::TIEBREAKER_FIELD_SET:
                $tieBreaker = $mapper->getTieBreaker();
                $fieldSet = new Fieldset\TieBreakerFieldset($tieBreaker);
                break;

            case self::BUTTON_FIELD_SET:
                $fieldSet = new Fieldset\ButtonFieldset();
                break;

            default:
                throw new \RuntimeException(
                    sprintf('An unknown field set was provided.')
                );
        }

        $fieldSet->setTranslator($this->getTranslator());
        $fieldSet->setTranslatorTextDomain($this->getTranslatorTextDomain());
        return $fieldSet;
    }


}
