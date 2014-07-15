<?php
namespace Season\Mail;

/**
 * Class InvitationMail
 *
 * @package Season\Mail
 */
class InvitationMail extends SeasonMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        $message =
            $this->translate('A new season is announced at %URL%.') . ' ' .
            $this->translate('We are happy to invite you to participate in the %NUMBER%. %ASSOCIATION% League.') . ' ' .
            $this->translate('The starting date is tentatively scheduled for the %DATE%.') .
            PHP_EOL . PHP_EOL .
            $this->translate('Base time: %BASE_TIME% min.') .
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
        $subject = $this->translate('Invitation - %NUMBER%. %ASSOCIATION% League');
        $this->makeReplacements($subject);

        return $subject;
    }


}