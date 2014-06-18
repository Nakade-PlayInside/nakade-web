<?php
namespace Season\View\Helper;

use Season\Entity\Season;
use Zend\View\Helper\AbstractHelper;

/**
 * Class RulesInfo
 *
 * @package Season\View\Helper
 */
class RulesInfo extends AbstractHelper
{
    private $info = array();
    /**
     * @param Season $season
     *
     * @return array
     */
    public function __invoke(Season $season)
    {

       $timeName =  $this->getView()->translate('Time');
       $timeValue =  $season->getTime()->getBaseTime() . ' ' . $this->getView()->translate('min.');

       $this->addInfo($timeName, $timeValue);

       if ($season->getTime()->hasAdditionalTime()) {
           $byoyomiName =  $this->getView()->translate('Byoyomi');
           $byoyomiValue =   $season->getTime()->getMoves() . '/' . $season->getTime()->getAdditionalTime();

           $this->addInfo($byoyomiName, $byoyomiValue);
       }

       $komiName = $this->getView()->translate('Komi');
       $komiValue = $season->getKomi() . ' ' . $this->getView()->translate('points');

       $this->addInfo($komiName, $komiValue);

       return $this->info;

    }

    private function addInfo($name, $value)
    {
        $info = sprintf('%s: %s',
            $name,
            $value
        );

        $this->info[] = $info;
    }


}
