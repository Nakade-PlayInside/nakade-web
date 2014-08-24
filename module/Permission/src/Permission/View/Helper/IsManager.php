<?php
namespace Permission\View\Helper;

/**
 * Class IsManager
 *
 * @package Permission\View\Helper
 */
class IsManager extends DefaultViewHelper
{
    /**
     * has a managing role, e.g. admin, referee or league manager
     *
     * @return bool
     */
    public function __invoke()
    {
       return $this->getVoterService()->isManager();

    }

}
