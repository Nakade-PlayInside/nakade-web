<?php
namespace Season\Mail;
use Season\Entity\Match;

/**
 * Class ScheduleMail
 *
 * @package Season\Mail
 */
class ScheduleMail extends SeasonMail
{

    private $matches;

    /**
     * @return string
     */
    public function getMailBody()
    {
         $message =
            $this->translate('This is your actual schedule for the %NUMBER%.%ASSOCIATION% League at %URL%.') .
            PHP_EOL . PHP_EOL .
            $this->getSchedule() .
            PHP_EOL . PHP_EOL .
            $this->translate('All matches are officially played on KGS.') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate('Base time: %BASE_TIME% min.') .
            PHP_EOL .
            $this->translate('Byoyomi') . ': %BYOYOMI%, %MOVES%/%ADDITIONAL_TIME%' .
            PHP_EOL .
            $this->translate('Komi') . ': %KOMI%' .
            PHP_EOL .
            $this->translate('Match day') . ': %MATCH_DAY%, %CYCLE% - %TIME%' .
            PHP_EOL . PHP_EOL .
            $this->translate('You have to provide your KGS name, so your opponent can recognize you.') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate('In case of a match appointment, find your actual schedule on your dashboard .') . ' ' .
            $this->translate('You can also download your actual schedule for ICal.') .
            $this->translate('The deadline for appointments are 3 days before a match.') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate('You will receive an email some days before a match.') . ' ' .
            $this->translate('Please keep an eye on your mailbox - also in case of appointments.') .
            PHP_EOL . PHP_EOL .
            $this->translate('After a match, both players may enter the result.') . ' ' .
            $this->translate('Be aware on automatic emails if the result is not entered in time.') . ' ' .
            $this->translate('There is a deadline of 72h after a match.') . ' ' .
            $this->translate('Afterwards the match is automatically suspended.') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->translate('Any further details on the tournament rules, you can find online.') . ' ' .
            $this->translate('If you have problems, please contact a league manager.') . ' ' .
            $this->translate('We hope you enjoy your matches and have a wonderful time on %URL%.') . ' ' .
            PHP_EOL . PHP_EOL .
            $this->getSignature()->getSignatureText();

        $this->makeReplacements($message);

        return $message;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        $subject = $this->translate('Your Schedule - %ASSOCIATION% League');
        $this->makeReplacements($subject);

        return $subject;
    }

    /**
     * @return string
     */
    private function getSchedule()
    {
        $schedule = '';
        foreach ($this->getMatches() as $match) {
            $schedule .= $this->getPairing($match);
        }

        return $schedule;
    }

    /**
     * @param Match $match
     *
     * @return string
     */
    private function getPairing(Match $match)
    {
        $matchInfo = sprintf('%s  %s (%s) - %s (%s)',
            $match->getDate()->format('d.m. H:i'),
            $match->getBlack()->getShortName(),
            $match->getBlack()->getOnlineName(),
            $match->getWhite()->getShortName(),
            $match->getWhite()->getOnlineName()
        );
        $matchInfo = $matchInfo . PHP_EOL;
        return $matchInfo;
    }

    /**
     * @param array $matches
     */
    public function setMatches($matches)
    {
        $this->matches = $matches;
    }

    /**
     * @return array
     */
    public function getMatches()
    {
        return $this->matches;
    }

}