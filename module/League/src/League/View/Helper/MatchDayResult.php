<?php
namespace League\View\Helper;

use League\Standings\ResultInterface;
use Zend\View\Helper\AbstractHelper;
use Season\Entity\Match;

/**
 * Class MatchDayResult
 *
 * @package League\View\Helper
 */
class MatchDayResult extends AbstractHelper implements ResultInterface
{
    /**
     * get the result as shortcut info
     *
     * @param Match $match
     *
     * @return string
     */
    public function __invoke(Match $match)
    {
        $info = $match->getDate()->format('d.m.  H:i');
        if ($match->hasResult()) {
            $info = $this->getResult($match);
        }
        return $info;
    }

    /**
     * @param Match $match
     *
     * @return string
     */
    private function getResult(Match $match)
    {
        $sm = $this->getView()->getHelperPluginManager()->getServiceLocator();

        /* @var $resultList \League\Standings\Results */
        $resultList = $sm->get('League\Services\ResultService');
        $resultId = $match->getResult()->getId();
        $result = $this->getView()->result($match);

        $title = $resultList->getResult($resultId);

        switch($resultId) {
            case self::RESIGNATION:
                $show  = 'R';
                break;
            case self::BYPOINTS:
                $show = $match->getPoints();
                break;
            case self::DRAW:
                $show = 'D';
                break;
            case self::FORFEIT:
                $show = 'F';
                break;
            case self::SUSPENDED:
                $show = 'S';
                break;
            case self::ONTIME:
                $show = 'T';
                break;
            default:
                $show = 'R';
        }

        return sprintf("<span title=\"%s\">%s (%s)</span>", $title, $result, $show);

    }


}
