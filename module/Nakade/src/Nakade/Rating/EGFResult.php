<?php

namespace Nakade\Rating;

use User\Entity\User;
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
    public function __construct(Result $result=null)
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
        if ($this->hasNoResult()) {
            return null;
        }

        if ($this->isSuspended()) {
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
    private function hasNoResult()
    {
        return is_null($this->getResult());
    }

    /**
     * @return bool
     */
    private function isSuspended()
    {
        return $this->getResult()->getResultType()->getId() == ResultInterface::SUSPENDED;
    }

    /**
     * @return bool
     */
    public function isDraw()
    {
        return $this->getResult()->getResultType()->getId() == ResultInterface::DRAW;
    }

    /**
     * @param Result $result
     */
    public function setResult(Result $result)
    {
        $this->result = $result;
    }


    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

}
