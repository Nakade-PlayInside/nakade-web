<?php

namespace Mail\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nakade\Abstracts\AbstractTranslation;

class MailSignatureService extends AbstractTranslation implements FactoryInterface
{

    private $clubInfo = array();

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');

        if (!isset($config['nakade_mail']['signature'])) {
            throw new \RuntimeException(
                'Mail Signature configuration not found.');
        }

        $this->clubInfo = $config['nakade_mail']['signature'];

        //configuration
        $textDomain = isset($config['Mail']['text_domain']) ?
            $config['Mail']['text_domain'] : null;

        $translator = $services->get('translator');

        //@todo: proof if this text domain setting is already doing the job
        $this->setTranslator($translator, $textDomain);

        return $this;
    }

    /**
     * @return string
     */
    public function getSignatureText()
    {
        $signature = $this->translate("May the stones be with you.") . PHP_EOL .
            $this->translate("Your %TEAM%.") . PHP_EOL . PHP_EOL .
            '%CLUB%' . PHP_EOL .
            $this->translate("Court of Registration") . ' : ' . '%COURT%' . PHP_EOL .
            $this->translate("Register No.") . ' : ' . '%REG_NO%';

        $signature = str_replace('%TEAM%', $this->getClubInfoType('team'), $signature);
        $signature = str_replace('%CLUB%', $this->getClubInfoType('club'), $signature);
        $signature = str_replace('%COURT%', $this->getClubInfoType('register_court'), $signature);
        $signature = str_replace('%REG_NO%', $this->getClubInfoType('register_no'), $signature);

        return $signature;
    }


    /**
     * @param string $type
     *
     * @return string
     */
    private function getClubInfoType($type)
    {
        if (array_key_exists($type, $this->clubInfo)) {
            return $this->clubInfo[$type];
        }

        return '--';
    }


}
