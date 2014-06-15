<?php
namespace League\View\Helper;

use Season\Entity\Match;
use Zend\View\Helper\AbstractHelper;

/**
 * Class MyColor
 *
 * @package League\View\Helper
 */
class MyColor extends AbstractHelper implements HighlightInterface
{

    /**
     * @param Match $match
     *
     * @return string
     */
    public function __invoke(Match $match)
    {
       $id = $this->getView()->identity()->getId();

       $color = 'black';
       if ($id == $match->getWhite()->getId()) {
            $color = 'white';
       }
       return $color;
    }

}
