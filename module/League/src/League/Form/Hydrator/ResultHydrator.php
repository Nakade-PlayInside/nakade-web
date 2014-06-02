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
          'points' => $match->getPoints()
        );
    }

    private function setValues(Match $match)
    {
        if (!is_null($match->getWinner())) {
            $this->winnerId = $match->getWinner()->getId();
        }
        if (!is_null($match->getResult())) {
            $this->resultId = $match->getResult()->getId();
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
        //todo: winner
        //todo: winner can be null
        /* @var $match \Season\Entity\Match */
        $match->setPoints($data['points']);
        $result = $this->getResult($data['resultId']);
        $match->setResult($result);
        return $match;
    }

    /**
     * @param int $resultId
     *
     * @return null|\League\Entity\Result
     */
    private function getResult($resultId)
    {
        return $this->getEntityManager()->getReference('League\Entity\Result', intval($resultId));
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

}
