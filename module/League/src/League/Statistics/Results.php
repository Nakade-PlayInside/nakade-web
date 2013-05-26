<?php
namespace League\Statistics;


/**
 * Result types of matches. Instead of having 
 * a data table, this is more convenient 
 * for i18N. 
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class Results extends AbstractTranslator
{
 
    const RESIGNATION=1;
    const BYPOINTS=2;
    const DRAW=3;
    const FORFEIT=4;
    const SUSPENDED=5;
    const ONTIME=6;
    
    
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
        $ontime      = $this->translate("Lost on Time");
        
        
        return array(
            Results::RESIGNATION => $resignation,
            Results::BYPOINTS    => $byPoints,
            Results::DRAW        => $draw,
            Results::FORFEIT     => $forfeit,
            Results::SUSPENDED   => $suspended,
            Results::ONTIME      => $ontime,
        );
    }
    
    /**
     * Return the result as a string if existing, otherwise
     * null is returned
     * @param int $resultId
     * @return mixed 
     */
    public function getResult($resultId)
    {
        $resultTypes = $this->getResultTypes();
        if(array_key_exists($resultId, $resultTypes))
              return $resultTypes[$resultId];
        
        return null;
    }        
    
}

?>
