<?php
namespace League\View\Helper;

use League\Standings\ResultInterface;
use Zend\View\Helper\AbstractHelper;
use Season\Entity\Match;
use User\Entity\User;

/**
 * Class Winner
 *
 * @package League\View\Helper
 */
class Winner extends AbstractHelper implements ResultInterface
{
    /**
     * @param Match $match
     * @param User  $user
     *
     * @return string
     */
    public function __invoke(Match $match, User $user)
    {
        if (!$match->hasResult() ||
            $match->getResult()->getResultType()->getId() == ResultInterface::DRAW ||
            $match->getResult()->getResultType()->getId() == ResultInterface::SUSPENDED) {
            return '';
        } elseif ($match->getResult()->getWinner() == $user) {
            return 'underline;';
        }
        return '';
    }


}
