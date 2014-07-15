<?php
namespace Season\Schedule;

use Nakade\Abstracts\AbstractTranslation;
use Zend\I18n\Translator\Translator;


/**
 * Class DateHelper
 *
 * @package Season\Schedule
 */
class DateHelper extends AbstractTranslation implements WeekDayInterface
{

    /**
     * @param Translator $translator
     * @param null       $textDomain
     */
    public function __construct(Translator $translator, $textDomain=null)
    {
        $this->setTranslator($translator, $textDomain);
    }

    /**
     * @return array
     */
    public function getWeekdays()
    {
        return array(
            self::MONDAY => $this->translate('Monday'),
            self::TUESDAY => $this->translate('Tuesday'),
            self::WEDNESDAY => $this->translate('Wednesday'),
            self::THURSDAY => $this->translate('Thursday'),
            self::FRIDAY => $this->translate('Friday'),
            self::SATURDAY => $this->translate('Saturday'),
            self::SUNDAY => $this->translate('Sunday')
        );
    }

    /**
     * @return array
     */
    public function getCycles()
    {
        return array(
            self::DAILY => $this->translate('daily'),
            self::WEEKDAYS => $this->translate('on weekdays'),
            self::WEEKLY => $this->translate('weekly'),
            self::FORTNIGHTLY => $this->translate('fortnightly'),
            self::ALL_THREE_WEEKS => $this->translate('all 3 weeks'),
            self::MONTHLY => $this->translate('monthly'),
        );
    }

    /**
     * @param int $day
     *
     * @return string
     */
    public function getDay($day)
    {

        $weekDays = $this->getWeekdays();
        if (array_key_exists($day, $weekDays)) {
            return $weekDays[$day];
        }

        return $this->translate('unknown');
    }

    /**
     * @param int $cycle
     *
     * @return string
     */
    public function getCycle($cycle)
    {

        $cycles = $this->getCycles();
        if (array_key_exists($cycle, $cycles)) {
            return $cycles[$cycle];
        }

        return $this->translate('unknown');
    }

}
