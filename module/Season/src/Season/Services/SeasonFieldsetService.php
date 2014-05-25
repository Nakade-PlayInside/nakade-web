<?php

namespace Season\Services;

use Nakade\Abstracts\AbstractFieldsetFactory;
use Season\Form\Fieldset\ButtonFieldset;
use Season\Form\Fieldset\SeasonFieldset;
use Season\Form\Fieldset\TieBreakerFieldset;
use Season\Form\Fieldset\TimeFieldset;
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
    const BYOYOMI_FIELD_SET = 'byoyomi';
    const BUTTON_FIELD_SET = 'button';
    const SEASON_FIELD_SET = 'season';

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

        //return a sorted array of field sets just for the season form only
        $list = array();
        $list[]=$this->getFieldset(self::SEASON_FIELD_SET);
        $list[]=$this->getFieldset(self::TIEBREAKER_FIELD_SET);
        $list[]=$this->getFieldset(self::BYOYOMI_FIELD_SET);
        $list[]=$this->getFieldset(self::BUTTON_FIELD_SET);

        return $list;
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
                $fieldSet = new TieBreakerFieldset($tieBreaker);
                break;

            case self::SEASON_FIELD_SET:
                $fieldSet = new SeasonFieldset();
                break;

            case self::BYOYOMI_FIELD_SET:
                $extraTime = $mapper->getByoyomi();
                $fieldSet = new TimeFieldset($extraTime);
                break;

            case self::BUTTON_FIELD_SET:
                $fieldSet = new ButtonFieldset();
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
