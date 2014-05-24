<?php
namespace Season\View\Helper;

use Season\Entity\Season;
use Zend\View\Helper\AbstractHelper;

/**
 * Class SeasonTitle
 *
 * @package Season\View\Helper
 */
class SeasonTitle extends AbstractHelper
{
    /**
     * @param Season $season
     *
     * @return string
     */
    public function __invoke(Season $season)
    {
       $translate = $this->getView()->plugin('translate');
       if (is_null($season)) {
           return $translate('Season Overview');
       } else {
            return sprintf('%s %s %s',
                $translate('Season Overview'),
                $season->getAssociation()->getName(),
                $translate('League')
            );
       }
    }
}
