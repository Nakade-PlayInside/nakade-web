<?php
namespace League\View\Helper;

use Zend\View\Helper\AbstractHelper;
use User\Entity\User;

/**
 * Class Active
 *
 * @package League\View\Helper
 */
class HighlightUser extends AbstractHelper implements HighlightInterface
{

    /**
     * @param User $user
     *
     * @return string
     */
    public function __invoke(User $user)
    {
       $color = $this->getView()->cycle(array(self::ALTERNATING_COLOR, self::BG_COLOR))->next();
       $id = $this->getView()->identity()->getId();

       if ($id == $user->getId()) {
            $color = self::HIGHLIGHT_COLOR;
       }
       return $color;
    }

}
