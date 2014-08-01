<?php
namespace User\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * Class ShowStage
 *
 * @package User\View\Helper
 */
class ShowStage extends AbstractHelper
{
    /**
     * @param bool $isVerified
     * @param bool $isDue
     *
     * @return string
     */
    public function __invoke($isVerified, $isDue=false)
    {
        $class = 'unverified';
        if ($isVerified) {
            $class = 'verified';
        } elseif ($isDue) {
            $class = 'isDue';
        }

        return $class;
    }
}
