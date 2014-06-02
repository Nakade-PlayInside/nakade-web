<?php
namespace League\View\Helper;

use Season\Entity\Match;
use Zend\View\Helper\AbstractHelper;

/**
 * Class HasResult
 *
 * @package League\View\Helper
 */
class HasResult extends AbstractHelper
{

    /**
     * @param Match $match
     *
     * @return string
     */
    public function __invoke(Match $match)
    {
        $isPlayed ='';
        if (!is_null($match->getResult())) {
            $isPlayed = "line-through;";
        }

        return $isPlayed;
    }

}
