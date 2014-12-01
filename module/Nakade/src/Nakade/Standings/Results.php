<?php
namespace Nakade\Standings;

use Nakade\Abstracts\AbstractTranslation;
use Nakade\Result\ResultInterface;
/**
 * Result types of matches. Instead of having
 * a data table, this is more convenient
 * for i18N.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class Results extends AbstractTranslation implements ResultInterface
{

    /**
     * returns an array of all result types as string values.
     * These string can be translated.
     *
     * @return array of strings
     */
    public function getResultTypes()
    {
        $resignation = $this->translate("Resignation");
        $byPoints    = $this->translate("Win by Points");
        $draw        = $this->translate("Draw");
        $forfeit     = $this->translate("Lost by Forfeit");
        $suspended   = $this->translate("Game Suspended");
        $onTime      = $this->translate("Lost on Time");


        return array(
            self::RESIGNATION => $resignation,
            self::BYPOINTS    => $byPoints,
            self::DRAW        => $draw,
            self::FORFEIT     => $forfeit,
            self::SUSPENDED   => $suspended,
            self::ONTIME      => $onTime,
        );
    }

    /**
     * returns an array of all result types as string values.
     * These string can be translated.
     *
     * @return array of strings
     */
    public function getResultTypeAbbreviation()
    {
        $resignation = $this->translate("R");
        $points      = $this->translate("Pt");
        $draw        = $this->translate("D");
        $forfeit     = $this->translate("F");
        $suspended   = $this->translate("S");
        $onTime      = $this->translate("T");


        return array(
            self::RESIGNATION => $resignation,
            self::DRAW        => $draw,
            self::BYPOINTS    => $points,
            self::FORFEIT     => $forfeit,
            self::SUSPENDED   => $suspended,
            self::ONTIME      => $onTime,
        );
    }

    /**
     * @return array of strings
     */
    public function getShortResultTypes()
    {
        $resignation = $this->translate("Resignation");
        $draw        = $this->translate("Draw");
        $forfeit     = $this->translate("Forfeit");
        $suspended   = $this->translate("Suspended");
        $onTime      = $this->translate("Time");


        return array(
            self::RESIGNATION => $resignation,
            self::DRAW        => $draw,
            self::FORFEIT     => $forfeit,
            self::SUSPENDED   => $suspended,
            self::ONTIME      => $onTime,
        );
    }

    /**
     * @return array of strings
     */
    public function getLegend()
    {
        //R = Resignation
        return array(
            self::RESIGNATION => $this->getLegendInfo(self::RESIGNATION),
            self::DRAW        => $this->getLegendInfo(self::DRAW),
            self::FORFEIT     => $this->getLegendInfo(self::FORFEIT),
            self::SUSPENDED   => $this->getLegendInfo(self::SUSPENDED),
            self::ONTIME      => $this->getLegendInfo(self::ONTIME),
        );
    }

    /**
     * @param array $matches
     *
     * @return array
     */
    public function getLegendByMatches(array $matches)
    {
        $legend = array();

        /* @var $match \Season\Entity\Match */
        foreach ($matches as $match) {
            if (!$match->hasResult() || $match->getResult()->getResultType()->getId() == self::BYPOINTS) {
                continue;
            }
            $resultId = $match->getResult()->getResultType()->getId();
            $info = $this->getLegendInfo($resultId);

            if (false === in_array($info, $legend)) {
                $legend[] = $info;
            }
        }

        return $legend;
    }

    /**
     * @param int $resultId
     *
     * @return string
     */
    private function getLegendInfo($resultId)
    {
        return $this->getAbbreviation($resultId) . ' = ' . $this->getShortForm($resultId);
    }

    /**
     * @param int $resultId
     *
     * @return mixed
     */
    public function getAbbreviation($resultId)
    {
        $resultTypes = $this->getResultTypeAbbreviation();
        if (array_key_exists($resultId, $resultTypes)) {
            return $resultTypes[$resultId];
        }
        return null;
    }

    /**
     * @param int $resultId
     *
     * @return mixed
     */
    public function getShortForm($resultId)
    {
        $resultTypes = $this->getShortResultTypes();
        if (array_key_exists($resultId, $resultTypes)) {
            return $resultTypes[$resultId];
        }
        return null;
    }


    /**
     * Return the result as a string if existing, otherwise
     * null is returned
     *
     * @param int $resultId
     *
     * @return mixed
     */
    public function getResult($resultId)
    {
        $resultTypes = $this->getResultTypes();
        if (array_key_exists($resultId, $resultTypes)) {
            return $resultTypes[$resultId];
        }
        return null;
    }

}
