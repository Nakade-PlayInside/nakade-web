<?php
namespace League\View\Helper;

use Season\Entity\Match;
use Zend\View\Helper\AbstractHelper;
use User\Entity\User;

/**
 * Class Opponent
 *
 * @package League\View\Helper
 */
class Opponent extends AbstractHelper implements HighlightInterface
{

    /**
     * @param Match $match
     *
     * @return User
     */
    public function __invoke(Match $match)
    {
       $id = $this->getView()->identity()->getId();


       $opponent = $match->getBlack();
       if ($id == $match->getBlack()->getId()) {
            $opponent = $match->getWhite();
       }

       return $opponent;
    }

}
