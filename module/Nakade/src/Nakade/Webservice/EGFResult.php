<?php

namespace Nakade\Webservice;

use Entities\User;
use League\Entity\Result;
use Nakade\Result\ResultInterface;

/**
 * Class EGFResult
 *
 * @package Nakade\Webservice
 */
class EGFResult
{
    const RESULT_DRAW = 0.5;
    const RESULT_VICTORY = 1.0;
    const RESULT_LOSS = 0;

    private $result;

    /**
     * @param Result $result
     */
    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * The achieved match result by the EGF is 1 for a win, 0 for a loss and 0.5 for a draw.
     * Null is returned if match has no result or is suspended.
     *
     * @param User $player
     *
     * @return float|null
     */
    public function getAchievedResult(User $player)
    {
        if ($this->hasResult()) {
            return null;
        }

        if ($this->isDraw()) {
            return floatval(self::RESULT_DRAW);
        }

        if ($this->isWinner($player)) {
            return floatval(self::RESULT_VICTORY);
        }

        return floatval(self::RESULT_LOSS);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    private function isWinner(User $user)
    {
        return $this->getResult()->getWinner()->getId() == $user->getId();
    }

    /**
     * @return bool
     */
    private function hasResult()
    {
        return !is_null($this->getResult()) &&
        $this->getResult()->getResultType()->getId() != ResultInterface::SUSPENDED;
    }

    /**
     * @return bool
     */
    private function isDraw()
    {
        return $this->getResult()->getResultType()->getId() == ResultInterface::DRAW;
    }

    /**
     * @return Result
     */
    private function getResult()
    {
        return $this->result;
    }

}
