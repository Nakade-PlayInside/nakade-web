<?php

namespace League\Services;

use User\Entity\User;
use League\Entity\Match;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\I18n\Translator\Translator;

/**
 * Makes an iCal match schedule for a specific user
 *
 * @author Dr.Holger Maerz <grrompf@gmail.com>
 */
class ICalService implements FactoryInterface
{
    private $organizer = 'holger@nakade.de';
    private $location  = 'Kiseido Go Server';
    private $translator = null;
    private $textDomain = null;


    /**
     * creates the authController. Binds the authentication service and
     * the authentication form.
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return \Authentication\Controller\AuthController
     */
    public function createService(ServiceLocatorInterface $services)
    {

       // $serviceManager = $services->getServiceLocator();

        $config  = $services->get('config');
        if ($config instanceof \Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }

        //configuration
        $textDomain = isset($config['League']['text_domain']) ?
            $config['League']['text_domain'] : null;

        $this->setTextDomain($textDomain);

        /* @var $translator Translator */
        $translator = $services->get('translator');
        $this->setTranslator($translator);

        return $this;
    }

    /**
     * @param int   $userId
     * @param array $mySchedule
     *
     * @return string
     */
    public function getICalSchedule($userId, array $mySchedule)
    {
        // SEQUENCE: 0++
        // UNIQUE Id similar for updating
        $content = "BEGIN:VCALENDAR" . PHP_EOL .
            "VERSION:2.0" . PHP_EOL .
            "PRODID: http://www.nakade.de" . PHP_EOL .
            "CALSCALE:GREGORIAN" . PHP_EOL .
            "METHOD:PUBLISH" . PHP_EOL .
            $this->getEvents($userId, $mySchedule) .
            "END:VCALENDAR" . PHP_EOL;

        return "$content";

    }

    /**
     * @param int   $userId
     * @param array $mySchedule
     *
     * @return string
     */
    public function getEvents($userId, array $mySchedule)
    {
        $myEvent = '';

        /* @var $match \League\Entity\Match */
        foreach ($mySchedule as $match) {
            $myEvent .= $this->getMyEvent($match, $userId);
        }

        return $myEvent;
    }

    /**
     * @param Match $match
     * @param int   $userId
     *
     * @return string
     */
    private function getMyEvent(Match $match, $userId)
    {
        return "BEGIN:VEVENT" . PHP_EOL .
        "DTSTART:" . $this->getDtStart($match) . PHP_EOL .
        "DTEND:" . $this->getDtEnd($match) . PHP_EOL .
        "DTSTAMP:" . date('Ymd').'T'.date('His') . PHP_EOL .
        "UID:" . $this->getUnique($match) . PHP_EOL .
        "SEQUENCE:" . $match->getSequence() . PHP_EOL .
        "LOCATION:" . $this->getLocation() . PHP_EOL .
        "DESCRIPTION:" . $this->getDescription($match, $userId) . PHP_EOL .
        "URL;VALUE=URI:nakade.de"  . PHP_EOL .
        "SUMMARY:" . $this->getSummary($match, $userId) . PHP_EOL .
        "CATEGORIES:GO" . PHP_EOL .
        "ORGANIZER:mailto:" . $this->getOrganizer() . PHP_EOL .
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
     * @param int   $userId
     *
     * @return User
     */
    private function getOpponent(Match $match, $userId)
    {
        $opponent = $match->getBlack();
        if ( $opponent->getId() == $userId) {
            $opponent = $match->getWhite();
        }
        return $opponent;
    }

    /**
     * @param Match $match
     * @param int   $userId
     *
     * @return string
     */
    private function getMyColor(Match $match, $userId)
    {
        $myColor = $this->translate("white");
        if ( $match->getBlack()->getId() == $userId) {
            $myColor = $this->translate("black");
        }
        return $myColor;
    }

    /**
     * @param Match $match
     * @param int   $userId
     *
     * @return string
     */
    private function getDescription(Match $match, $userId)
    {
        $opponent = $this->getOpponent($match, $userId);
        $matchInfo = $this->translate("My match vs %name%");
        $matchInfo = str_replace("%name%", $opponent->getShortName(), $matchInfo);

        $kgsName = $this->translate("KGS name: %name%");
        $kgsName = str_replace("%name%", $opponent->getOnlineName(), $kgsName);

        $colorInfo = $this->translate("my color is %color%");
        $colorInfo = str_replace("%color%", $this->getMyColor($match, $userId), $colorInfo);

        return sprintf("%s, %s, %s. %s",
            $matchInfo,
            $kgsName,
            $colorInfo,
            $this->getRules()
        );
    }

    /**
     * @param Match $match
     * @param int   $userId
     *
     * @return string
     */
    private function getSummary(Match $match, $userId)
    {
        $opponent = $this->getOpponent($match, $userId);
        $info = $this->translate("Nakade League Match vs %name%");
        $info = str_replace("%name%", $opponent->getShortName(), $info);

        return $info;

    }

    /**
     * @return string
     */
    private function getRules()
    {
        return sprintf("60min, 30/10, 7.5 Komi");
    }

    /**
     * @return string
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Translator $translator
     *
     * @return $this
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator=$translator;
        return $this;
    }

    /**
     * Returns translator used in object
     *
     * @return null|Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param string     $textDomain
     *
     * @return $this
     */
    public function setTextDomain ($textDomain)
    {
        $this->textDomain=$textDomain;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getTextDomain ()
    {
        return $this->textDomain;
    }



    /**
     * @param string $message
     *
     * @return string
     */
    public function translate($message)
    {
        if (is_null($this->getTranslator()) || is_null($this->getTextDomain())) {
            return $message;
        }
        return $this->getTranslator()->translate($message, $this->getTextDomain());
    }



}
