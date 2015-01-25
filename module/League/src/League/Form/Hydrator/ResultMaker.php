<?php
namespace League\Form\Hydrator;

use League\Entity\Result;
use League\Entity\ResultType;
use Nakade\Result\ResultInterface;
use Nakade\Standings\Tiebreaker\HahnPointInterface;
use Season\Entity\Match;

/**
 * Class SeasonHydrator
 *
 * @package Season\Form
 */
class ResultMaker implements ResultInterface, HahnPointInterface
{
    private $result;

    /**
     * @param Result $result
     */
    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * @param array  $data
     * @param object $match
     *
     * @return object
     */
    public function validate()
    {

        if ($this->getResult()->getResultType()->getId() == self::BYPOINTS) {

            $points = $this->getResult()->getPoints();

            //too much points -> resignation
            if ($points > self::OFFSET_POINTS) {

                $type = $this->getResult()->getResultType();
                $type->setId(self::RESIGNATION);
                $this->getResult()->setResultType($type);
            }
            elseif (empty($points)) {

            }
        }





    }

    /**
     * @return bool
     */
    private function getResultTypeById($resultTypeId)
    {

        return new ResultType();
       // $type->setId(self::RESIGNATION);
      //  $this->getResult()->setResultType($type);
    }


    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }



}
