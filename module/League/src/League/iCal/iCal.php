<?php
namespace League\iCal;

use League\Services\RepositoryService;
use Nakade\Abstracts\AbstractTranslation;
use Zend\Http\PhpEnvironment\Response as iCalResponse;
use Zend\Http\Headers;
use User\Entity\User;
use Season\Entity\Match;

/**
 * Class iCal
 *
 * @package League\iCal
 */
class iCal extends AbstractTranslation
{
    const FILE_NAME    = 'myNakade.iCal';
    private $organizer = 'holger@nakade.de';
    private $location  = 'Kiseido Go Server';
    private $userId;
    private $schedule;
    private $headers;
    private $repository;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->headers = new Headers();
        $this->headers->addHeaderLine('Content-Type', 'text/calendar; charset=utf-8')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . self::FILE_NAME . '"');
    }

    /**
     * @param int   $userId
     * @param array $schedule
     *
     * @return iCalResponse
     */
    public function getSchedule($userId, array $schedule)
    {
        $this->userId = $userId;
        $this->schedule = $schedule;

        $content = $this->getContent();

        $response = new iCalResponse();
        $response->setStatusCode(200);
        $response->setHeaders($this->headers);
        $response->setContent($content);

        return $response;
    }

    /**
     * @return string
     */
    private function getContent()
    {
        // SEQUENCE: 0++
        // UNIQUE Id similar for updating
        $content = "BEGIN:VCALENDAR" . PHP_EOL .
            "VERSION:2.0" . PHP_EOL .
            "PRODID: http://www.nakade.de" . PHP_EOL .
            "CALSCALE:GREGORIAN" . PHP_EOL .
            "METHOD:PUBLISH" . PHP_EOL .
            $this->getEvents() .
            "END:VCALENDAR" . PHP_EOL;

        return "$content";

    }

    /**
     * @return string
     */
    private function getEvents()
    {
        $myEvent = '';

        /* @var $match \Season\Entity\Match */
        foreach ($this->schedule as $match) {
            $myEvent .= $this->getMyEvent($match);
        }

        return $myEvent;
    }

    /**
     * for details see http://www.kanzaki.com/docs/ical/valarm.html
     * or https://de.wikipedia.org/wiki/ICalendar
     *
     * @param Match $match
     *
     * @return string
     */
    private function getMyEvent(Match $match)
    {
        return "BEGIN:VEVENT" . PHP_EOL .
        "DTSTART:" . $this->getDtStart($match) . PHP_EOL .
        "DTEND:" . $this->getDtEnd($match) . PHP_EOL .
        "DTSTAMP:" . date('Ymd').'T'.date('His') . PHP_EOL .
        "UID:" . $this->getUnique($match) . PHP_EOL .
        "SEQUENCE:" . $match->getSequence() . PHP_EOL .
        "LOCATION:" . $this->getLocation() . PHP_EOL .
        "DESCRIPTION:" . $this->getDescription($match) . PHP_EOL .
        "URL;VALUE=URI:nakade.de"  . PHP_EOL .
        "SUMMARY:" . $this->getSummary($match) . PHP_EOL .
        "CATEGORIES:GO" . PHP_EOL .
        "ORGANIZER:mailto:" . $this->getOrganizer() . PHP_EOL .
        "BEGIN:VALARM" . PHP_EOL .
        "TRIGGER:-PT30M" . PHP_EOL .
        "REPEAT:2" . PHP_EOL .
        "DURATION:PT15M" . PHP_EOL .
        "ACTION:AUDIO" . PHP_EOL .
        "END:VALARM" . PHP_EOL .
        "END:VEVENT" . PHP_EOL ;
    }
    /**
     * @param Match $match
     *
     * @return string
     */
    private function getDtStart(Match $match)
    {
        $date = $match->getDate()->format('Ymd');
        $time = $match->getDate()->format('His');

        return sprintf("%sT%s",
            $date,
            $time
        );
    }

    /**
     * @param Match $match
     *
     * @return string
     */
    private function getDtEnd(Match $match)
    {

        $end = $match->getDate()->modify("+3 hour");
        $date = $end->format('Ymd');
        $time = $end->format('His');

        return sprintf("%sT%s",
            $date,
            $time
        );
    }

    /**
     * @param Match $match
     *
     * @return string
     */
    private function getUnique(Match $match)
    {
        return sprintf("%s@nakade",
            $match->getId()
        );
    }

    /**
     * @param Match $match
     *
     * @return User
     */
    private function getOpponent(Match $match)
    {
        $opponent = $match->getBlack();
        if ( $opponent->getId() == $this->userId) {
            $opponent = $match->getWhite();
        }
        return $opponent;
    }

    /**
     * @param Match $match
     *
     * @return string
     */
    private function getMyColor(Match $match)
    {
        $myColor = $this->translate("white");
        if ( $match->getBlack()->getId() == $this->userId) {
            $myColor = $this->translate("black");
        }
        return $myColor;
    }

    /**
     * @param Match $match
     *
     * @return string
     */
    private function getDescription(Match $match)
    {
        $opponent = $this->getOpponent($match);
        $matchInfo = $this->translate("My match vs %name%");
        $matchInfo = str_replace("%name%", $opponent->getShortName(), $matchInfo);

        $kgsName = $this->translate("KGS name: %name%");
        $kgsName = str_replace("%name%", $opponent->getOnlineName(), $kgsName);

        $colorInfo = $this->translate("my color is %color%");
        $colorInfo = str_replace("%color%", $this->getMyColor($match), $colorInfo);

        return sprintf("%s, %s, %s. %s",
            $matchInfo,
            $kgsName,
            $colorInfo,
            $this->getRules()
        );
    }

    /**
     * @param Match $match
     *
     * @return string
     */
    private function getSummary(Match $match)
    {
        $opponent = $this->getOpponent($match);
        $info = $this->translate("Nakade League Match vs %name%");
        $info = str_replace("%name%", $opponent->getShortName(), $info);

        return $info;

    }

    /**
     * @return string
     */
    private function getRules()
    {
        $season = $this->getSeason();
        if (is_null($season)) {
            return $this->translate('No Info available');
        }

        $info =  $season->getTime()->getMatchInfo();
        if ($season->hasKomi()) {
            $info .= ', ' . $season->getKomi() . ' Komi';
        }
        return $info;
    }

    /**
     * @return null|\Season\Entity\Season
     */
    private function getSeason()
    {
        $leagueId = $this->getLeagueId();

        /* @var $mapper \League\Mapper\ScheduleMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::SCHEDULE_MAPPER);
        return $mapper->getSeasonRulesByLeague($leagueId);
    }

    /**
     * @return int
     */
    private function getLeagueId()
    {
        /* @var $match \Season\Entity\Match */
        $match = reset($this->schedule);
        if (false === $match) {
            return -1;
        }
        return $match->getLeague()->getId();
    }

    /**
     * @return string
     */
    public function getOrganizer()
    {
        //todo: getOrganizer from database
        return $this->organizer;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        //todo: getLocation from database
        return $this->location;
    }

    /**
     * @param RepositoryService $repository
     */
    public function setRepository(RepositoryService $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return RepositoryService
     */
    public function getRepository()
    {
        return $this->repository;
    }

}

