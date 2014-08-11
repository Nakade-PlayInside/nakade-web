<?php
namespace Moderator\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
/**
 * Class GetActivateAction
 *
 * @package Moderator\View\Helper
 */
class GetActivateAction extends AbstractHelper
{
    /**
     * @param bool $isActive
     *
     * @return string
     */
    public function __invoke($isActive)
    {
        $action = 'unDelete';
        if ($isActive) {
            $action = 'delete';
        }

        return $action;
    }
}
