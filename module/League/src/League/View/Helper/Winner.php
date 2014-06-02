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
        if (is_null($match->getResult()) ||
            $match->getResult()->getId() == ResultInterface::DRAW ||
            $match->getResult()->getId() == ResultInterface::SUSPENDED) {
            return '';
        } elseif ($match->getWinner() == $user) {
            return 'underline;';
        }
        return '';
    }


}
