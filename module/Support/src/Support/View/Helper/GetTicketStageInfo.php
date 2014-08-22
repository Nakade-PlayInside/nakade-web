<?php
namespace Support\View\Helper;

use Support\Entity\StageInterface;
use Zend\Form\View\Helper\AbstractHelper;
/**
 * Class GetTicketStageInfo
 *
 * @package Moderator\View\Helper
 */
class GetTicketStageInfo extends AbstractHelper implements StageInterface
{
    /**
     * @param int $stage
     *
     * @return string
     */
    public function __invoke($stage)
    {
        switch ($stage) {

            case self::STAGE_WAITING:
                $text = $this->translate('Waiting');
                break;

            case self::STAGE_REOPENED:
                $text = $this->translate('Reopened');
                break;

            case self::STAGE_IN_PROCESS:
                $text = $this->translate('In process');
                break;

            case self::STAGE_CANCELED:
                $text = $this->translate('Canceled');
                break;

            case self::STAGE_DONE:
                $text = $this->translate('Done');
                break;

            default:
                $text = $this->translate('New');
        }

        return $text;
    }

    /**
     * @param string $message
     *
     * @return string
     */
    private function translate($message)
    {
        $translate = $this->getView()->plugin('translate');
        return $translate($message);
    }

}
