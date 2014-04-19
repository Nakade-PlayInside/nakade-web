<?php
namespace League\Statistics;

use Nakade\Abstracts\AbstractTranslation;
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
        $draw        = $this->translate("Jigo");
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
