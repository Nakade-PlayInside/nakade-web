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
    public function __invoke(Season $season=null)
    {
       if (is_null($season)) {
           return '?';
       }
       return $season->getAssociation()->getName();

    }
}
