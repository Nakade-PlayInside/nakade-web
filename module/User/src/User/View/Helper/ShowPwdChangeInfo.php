<?php
namespace User\View\Helper;
use Zend\View\Helper\AbstractHelper;

/**
 * Class ShowPwdChangeInfo
 *
 * @package User\View\Helper
 */
class ShowPwdChangeInfo extends AbstractHelper
{

    /**
     * @param \DateTime $date
     *
     * @return mixed|string
     */
    public function __invoke($date)
    {
        $info = $this->translate('password was never changed');
        if (!empty($date)) {
            $changeDate = $date->format('d.m.Y');
            $info = $this->translate('last time edited on %pwdChangeDate%');
            $info = str_replace('%pwdChangeDate%', $changeDate, $info);
        }

        return $info;
    }

    /**
     * @param string $message
     *
     * @return string
     */
    private function translate($message)
    {
        $translate = $this->getView()->plugin('translate');
        return $translate($message);
    }

}
