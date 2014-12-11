<?php
namespace League\View\Helper;

use League\Entity\Player;
use Zend\View\Helper\AbstractHelper;

/**
 * Class GetPosition
 *
 * @package League\View\Helper
 */
class GetPosition extends AbstractHelper
{

    private static $player;

    /**
    * @param Player $player
     *
    * @return int|string
     */
    public function __invoke(Player $player)
    {
        $position = $player->getPosition();
        if ($this->isEqualPosition($player)) {
                $position = '';
        }
        $this->setPreviousPlayer($player);
        return $position;

    }

    /**
     * @param Player $player
     *
     * @return bool
     */
    private function isEqualPosition(Player $player)
    {
        return $this->hasPreviousPlayer() && $this->getPreviousPosition() == $player->getPosition();
    }

    /**
     * @return Player
     */
    private function hasPreviousPlayer()
    {
        $prev = $this->getPreviousPlayer();
        return !empty($prev);
    }

    /**
     * @return Player
     */
    private function getPreviousPlayer()
    {
        return self::$player;
    }

    /**
     * @param Player $player
     *
     * @return $this
     */
    private function setPreviousPlayer(Player $player)
    {
        self::$player = $player;
        return $this;
    }

    /**
     * @return int
     */
    private function getPreviousPosition()
    {
        return $this->getPreviousPlayer()->getPosition();
    }

}
