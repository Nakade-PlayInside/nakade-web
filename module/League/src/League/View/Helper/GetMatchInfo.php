<?php
namespace League\View\Helper;

use Season\Entity\Match;
use Zend\View\Helper\AbstractHelper;

/**
 * Class GetMatchInfo
 *
 * @package League\View\Helper
 */
class GetMatchInfo extends AbstractHelper
{

    /**
     * @param Match $match
     *
     * @return string
     */
    public function __invoke(Match $match)
    {

        $season = sprintf('%s - %s. %s',
            $match->getLeague()->getSeason()->getAssociation()->getName(),
            $match->getLeague()->getSeason()->getNumber(),
            $this->getView()->translate("Season")
        );

        $division = sprintf('%s. %s',
            $match->getLeague()->getNumber(),
            $this->getView()->translate("League")
        );

        $matchDay =  sprintf('%s. %s',
            $match->getMatchDay()->getMatchDay(),
            $this->getView()->translate('Match day')
        );

        $match = sprintf('%s - %s',
            $match->getBlack()->getShortName(),
            $match->getWhite()->getShortName()
        );

        return sprintf("%s - %s<br> %s: %s",
            $season,
            $division,
            $matchDay,
            $match
        );
    }
}
