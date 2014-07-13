<?php
namespace Season\Mail;

/**
 * Class ScheduleMail
 *
 * @package Season\Mail
 */
class ScheduleMail extends SeasonMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {

        $schedule = sprintf('test');
        //todo: info all matches, appointments, rules, results, dashboard, rememberMails
        $message =
            $this->translate('This is your actual schedule for the new season at %URL%.') .
            PHP_EOL . PHP_EOL .
            $schedule .
            PHP_EOL . PHP_EOL .
            $this->translate('All matches are officially played on KGS.') . ' ' .
            $this->translate('Time') . ': %BASE_TIME%' .
            PHP_EOL .
            $this->translate('Byoyomi') . ': %BYOYOMI%, %MOVES%/%ADDITIONAL_TIME%' .
            PHP_EOL .
            $this->translate('Komi') . ': %KOMI%' .
            PHP_EOL . PHP_EOL .
            $this->translate('Match day') . ': %MATCH_DAY%, %CYCLE% - %TIME%' .
            PHP_EOL .
            $this->translate('You have to provide your KGS name, so your opponent will recognize you.') . ' ' .
            PHP_EOL . PHP_EOL .


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

            $this->translate('Base time') . ': %BASE_TIME%' .
            PHP_EOL .
            $this->translate('Byoyomi') . ': %BYOYOMI%, %MOVES%/%ADDITIONAL_TIME%' .
            PHP_EOL .
            $this->translate('Komi') . ': %KOMI%' .
            PHP_EOL . PHP_EOL .
            $this->translate('Match day') . ': %MATCH_DAY%, %CYCLE% - %TIME%' .
            PHP_EOL . PHP_EOL .
            $this->translate('To ensure being in the league, please confirm your participation as soon as possible.') .
            PHP_EOL .
            $this->translate('You can do so by clicking on the link provided below.') .
            PHP_EOL . PHP_EOL . '%CONFIRM_LINK%' . PHP_EOL . PHP_EOL .
            $this->translate('Or you just login at %URL% and confirm your participation on your site.') .
            PHP_EOL .
            $this->translate('The deadline for confirming your participation is a month before starting date.') . ' ' .
            $this->translate('All later applications will be ignored.') .
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


}