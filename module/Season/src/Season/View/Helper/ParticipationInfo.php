<?php
namespace Season\View\Helper;

use Season\Entity\Season;
use Zend\View\Helper\AbstractHelper;

/**
 * Class ParticipationInfo
 *
 * @package Season\View\Helper
 */
class ParticipationInfo extends AbstractHelper
{
    /**
     * @param Season $season
     *
     * @return string
     */
    public function __invoke(Season $season)
    {
        if ($season->hasMatchDays()) {
            return sprintf('<a class="btn" href="#">%s</a>',
                $this->translate('Registration Closed')
            );
        } elseif ($season->isRegistered()) {
            return sprintf('<h5>%s</h5>', $this->translate("You registered already!"));
        } else {
            return sprintf("<a class='btn' href='%s'>%s</a>",
                $this->url('playerConfirm', array('action' => 'register', 'id' => $season->getId())),
                $this->translate('Register NOW!')
            );
        }
    }

    private function translate($message)
    {
        $translate = $this->getView()->plugin('translate');
        return $translate($message);
    }

    private function url($route, array $params=null)
    {
        $url = $this->getView()->plugin('url');
        return $url($route, $params);
    }

}
