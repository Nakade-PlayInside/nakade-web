<?php

namespace League\Services;

use League\iCal\iCal;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nakade\Abstracts\AbstractService;

/**
 * Makes an iCal match schedule for a specific user
 *
 * @author Dr.Holger Maerz <grrompf@gmail.com>
 */
class ICalService extends AbstractService
{
    private $iCal;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return $this
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config  = $services->get('config');

        //configuration
        $textDomain = isset($config['League']['text_domain']) ?
            $config['League']['text_domain'] : null;

        /* @var $translator \Zend\I18n\Translator\Translator */
        $translator = $services->get('translator');

        $repository = $services->get('League\Services\RepositoryService');

        $iCal = new iCal();
        $iCal->setTranslator($translator);
        $iCal->setTranslatorTextDomain($textDomain);
        $iCal->setRepository($repository);

        $this->setICal($iCal);

        return $this;
    }

    /**
     * @param int   $userId
     * @param array $mySchedule
     *
     * @return string
     */
    public function getSchedule($userId, array $mySchedule)
    {
        return $this->getICal()->getSchedule($userId, $mySchedule);
    }

    /**
     * @param iCal $iCal
     */
    public function setICal(iCal $iCal)
    {
        $this->iCal = $iCal;
    }

    /**
     * @return iCal
     */
    public function getICal()
    {
        return $this->iCal;
    }




}
