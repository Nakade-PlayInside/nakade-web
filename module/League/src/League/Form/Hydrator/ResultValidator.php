<?php
namespace League\Form\Hydrator;

use League\Entity\Result;
use League\Entity\ResultType;
use Nakade\Result\ResultInterface;
use Nakade\Standings\Tiebreaker\HahnPointInterface;
use Doctrine\ORM\EntityManager;

/**
 * Validates result and set the correct result type. In addition, the points are validated and corrected by fraction.
 *
 * @package League\Form\Hydrator
 */
class ResultValidator implements ResultInterface, HahnPointInterface
{
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Validates the match and result and returns true if settings were corrected.
     *
     * @param Result $result
     * @param bool $isHalfPointWin
     *
     * @return bool
     */
    public function validate(Result &$result, $isHalfPointWin = true)
    {

        if ($result->getResultType()->getId() == self::BYPOINTS) {

            $points = $result->getPoints();
            $resultTypeId = $this->getValidatedResultTypeId($points);
            $resultType = $this->getResultTypeById($resultTypeId);
            $result->setResultType($resultType);

            $newPoints = $this->getValidatedPoints($points, $isHalfPointWin);
            $result->setPoints($newPoints);
            return true;
        }
        return false;

    }

    /**
     * If too much points result type is resignation. If win by points = 0.0 it is a draw.
     *
     * @param int $points
     *
     * @return int
     */
    public function getValidatedResultTypeId($points)
    {

        //too much points -> resignation
        if ($points > self::OFFSET_POINTS) {

            return self::RESIGNATION;
        } elseif (empty($points)) {

            return self::DRAW;
        }

        return self::BYPOINTS;
    }

    /**
     * correcting fraction to .5 or .0
     *
     * @param float $points
     * @param bool $isHalfPointWin
     *
     * @return float
     */
    private function getValidatedPoints($points, $isHalfPointWin)
    {
        $double = $points * 2;
        if ($double != (int) $points) {
            $points = floor($points);

            if ($isHalfPointWin) {
                $points += 0.5;
            }
        }

        return $points;
    }

    /**
     * @param int $resultId
     *
     * @return ResultType
     */
    public function getResultTypeById($resultId)
    {
        return $this->getEntityManager()->getReference('League\Entity\ResultType', intval($resultId));
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }




}
