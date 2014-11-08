<?php
namespace Stats\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CloseGameStats
 *
 * @package Stats\Entity
 */
class CloseGameStats
{
    private $whiteWinsByPoints;
    private $blackWinsByPoints;
    private $blackLossByPoints;
    private $whiteLossByPoints;
    private $closestWin;
    private $closestLoss;
    private $avaragePointsForWin;
    private $avaragePointsForLoss;


    public function populate($data)
    {
        foreach ($data as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

}