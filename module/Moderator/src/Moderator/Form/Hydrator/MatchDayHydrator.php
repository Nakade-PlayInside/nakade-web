<?php
namespace Moderator\Form\Hydrator;

use Season\Entity\Match;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class LeagueManagerHydrator
 *
 * @package Moderator\Form\Hydrator
 */
class LeagueManagerHydrator implements HydratorInterface
{
    private $matchDate;
    private $matchTime;
    private $black;
    private $white;
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
          'matchDate'  => $this->matchDate,
          'matchTime' => $this->matchTime,
          'blackPlayer' => $this->black,
          'whitePlayer' => $this->white,
          'matchDay' => $match->getMatchDay()->getMatchDay(),
        );
    }

    private function setValues(Match $match)
    {
        if (!is_null($match->getDate())) {
            $this->matchDate  = $match->getDate()->format('Y-m-d');
            $this->matchTime = $match->getDate();
        }
        if (!is_null($match->getBlack())) {
            $this->black = $match->getBlack()->getName();
        }
        if (!is_null($match->getWhite())) {
            $this->white = $match->getWhite()->getName();
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
        $datetime = $data['matchDate']. ' ' . $data['matchTime'];
        $temp = new \DateTime($datetime);
        $match->setDate($temp);

        //updating for iCal
        $sequence = $match->getSequence() + 1;
        $match->setSequence($sequence);

        //todo:isset proof
        if ($data['changeColors']) {
           $black = $match->getBlack();
           $white = $match->getWhite();

           $match->setBlack($white);
           $match->setWhite($black);
        }

        return $match;
    }


    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

}
