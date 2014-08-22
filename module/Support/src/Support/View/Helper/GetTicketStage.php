<?php
namespace Support\View\Helper;

use Support\Entity\StageInterface;
use Zend\Form\View\Helper\AbstractHelper;
/**
 * Class GetTicketStage
 *
 * @package Moderator\View\Helper
 */
class GetTicketStage extends AbstractHelper implements StageInterface
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
                $class = 'time-16';
                break;

            case self::STAGE_REOPENED:
            case self::STAGE_IN_PROCESS:
                $class = 'green-ball-16';
                break;

            case self::STAGE_CANCELED:
                $class = 'red-ball-16';
                break;

            case self::STAGE_DONE:
                $class = 'yellow-ball-16';
                break;

            default:
                $class = 'grey-ball-16';
        }

        return $class;
    }
}
