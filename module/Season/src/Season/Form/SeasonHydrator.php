<?php
namespace Season\Form;

use Nakade\Abstracts\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Season\Entity\Season;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Form for making a new season
 */
class SeasonHydrator implements HydratorInterface
{


    public function extract($object)
    {
        /* @var $object \Season\Entity\Season */

        return array(
          'tiebreaker1' => $object->getTieBreaker1()->getId(),
          'tiebreaker2' => $object->getTieBreaker2()->getId(),
          'tiebreaker3' => $object->getTieBreaker3()->getId(),
          'winPoints' => $object->getWinPoints(),
          'komi' => $object->getKomi(),
          'number' => $object->getNumber()+1,
          'associationName' => $object->getAssociation()->getName(),
        );
    }

    public function hydrate(array $data, $object)
    {
        /* @var $object \Season\Entity\Season */
        $object->setId(null);
        $object->setNumber($data['number']);
        $object->setKomi($data['komi']);
        $object->setWinPoints($data['winPoints']);
        return $object;
    }
}
