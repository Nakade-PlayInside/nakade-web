<?php
namespace Support\View\Helper;

use Season\Entity\Association;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * Class GetAssociation
 * @package Support\View\Helper
 */
class GetAssociation extends AbstractHelper
{
    /**
     * @param Association $association
     *
     * @return string
     */
    public function __invoke(Association $association=null)
    {
        $name = '';
        if (!is_null($association)) {
            $name = $association->getName();
        }
        return $name;
    }
}
