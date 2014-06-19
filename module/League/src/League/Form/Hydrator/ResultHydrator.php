<?php
namespace League\Form\Hydrator;

use Season\Entity\Match;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class SeasonHydrator
 *
 * @package Season\Form
 */
class ResultHydrator implements HydratorInterface
{
    private $winnerId;
    private $resultId;
    private $entityManager;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    /**
     * @param object $match
     *
     * @return array
     */
    public function extract($match)
    {

        /* @var $match \Season\Entity\Match */
        $this->setValues($match);

        return array(
          'id' => $match->getId(),
          'winnerId' => $this->winnerId,
          'resultId' => $this->resultId,
          'points' => $match->getResult()->getPoints()
        );
    }

    private function setValues(Match $match)
    {
        if (!is_null($match->getResult()) && !is_null($match->getResult()->getWinner())) {
            $this->winnerId = $match->getResult()->getWinner()->getId();
        }
        if (!is_null($match->getResult())) {
            $this->resultId = $match->getResult()->getResultType()->getId();
        }

    }

    /**
     * @param array  $data
     * @param object $match
     *
     * @return object
     */
    public function hydrate(array $data, $match)
    {
        //todo: setResult(new Result())
        //todo: resultType, winner, points
        /* @var $match \Season\Entity\Match */
        if (isset($data['points'])) {
            $points = floatval($data['points']);
            $match->setPoints($points);
        }
        if (isset($data['winnerId'])) {
            $winner = $this->getWinner($match, $data['winnerId']);
            if (!is_null($winner)) {
                $match->setWinner($winner);
            }
        }
        if (isset($data['resultId'])) {
            $result = $this->getResult($data['resultId']);
            $match->setResult($result);
        }

        return $match;
    }

    /**
     * @param Match $match
     * @param int   $winnerId
     *
     * @return null|\User\Entity\User
     */
    private function getWinner(Match $match, $winnerId)
    {
        $winner = null;
        if ($winnerId == $match->getBlack()->getId()) {
            $winner = $match->getBlack();
        } elseif ($winnerId == $match->getWhite()->getId()) {
            $winner = $match->getWhite();
        }
        return $winner;
    }

    /**
     * @param int $resultId
     *
     * @return null|\League\Entity\ResultType
     */
    private function getResult($resultId)
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
