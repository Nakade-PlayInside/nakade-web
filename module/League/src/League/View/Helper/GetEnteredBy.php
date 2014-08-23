<?php
namespace League\View\Helper;

use Season\Entity\Match;
use Zend\View\Helper\AbstractHelper;

/**
 * Class GetEnteredBy
 *
 * @package League\View\Helper
 */
class GetEnteredBy extends AbstractHelper
{

    /**
     * @param Match $match
     *
     * @return string
     */
    public function __invoke(Match $match)
    {
        if (is_null($result = $match->getResult())) {
            return '';
        }

        $class = '';
        $info = '<span %CLASS%>%NAME%</span>';
        if (is_null($result->getEnteredBy())) {
            $class =  'class="autoResult"';
            $name = $this->getView()->translate("automatic");
        } else {
            $name = $match->getResult()->getEnteredBy()->getShortName();
        }

        $info = str_replace('%CLASS%', $class, $info);
        $info = str_replace('%NAME%', $name, $info);

        return $info;

    }

}
