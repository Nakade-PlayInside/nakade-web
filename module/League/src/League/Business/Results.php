<?php
namespace League\Business;


/**
 * Description of Results
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
    
    public function getResult($resultId)
    {
        $resultTypes = $this->getResultTypes();
        if(array_key_exists($resultId, $resultTypes))
              return $resultTypes[$resultId];
        
        return null;
    }        
    
}

?>
