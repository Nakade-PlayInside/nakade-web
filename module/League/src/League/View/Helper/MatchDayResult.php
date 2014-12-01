<?php
namespace League\View\Helper;

use Nakade\Result\ResultInterface;
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
            $info = $this->getInfo($match);
        }
        return $info;
    }

    /**
     * @param Match $match
     *
     * @return string
     */
    private function getInfo(Match $match)
    {

        $resultId = $match->getResult()->getResultType()->getId();
        $result = $this->getView()->result($match);
        $title = $this->getResult($resultId);

        if ($resultId == self::BYPOINTS) {
            $show = $match->getResult()->getPoints() . ' ' . $this->getAbbreviation($resultId);
        } else {
            $show = $this->getAbbreviation($resultId);
        }

        return sprintf("<span title=\"%s\">%s (%s)</span>", $title, $result, $show);

    }

    /**
     * @return \Nakade\Standings\Results
     *
     * @throws \RuntimeException
     */
    private function getResultService()
    {
        $sm = $this->getView()->getHelperPluginManager()->getServiceLocator();

        if (!$sm->has('League\Services\ResultService')) {
            throw new \RuntimeException(
                sprintf('Result service could not be found.')
            );
        }
        return $sm->get('League\Services\ResultService');
    }

    /**
     * @param int $resultId
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    private function getAbbreviation($resultId)
    {
        $resultService = $this->getResultService();
        return $resultService->getAbbreviation($resultId);
    }

    /**
     * @param int $resultId
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    private function getResult($resultId)
    {
        $resultService = $this->getResultService();
        return $resultService->getResult($resultId);
    }

}
