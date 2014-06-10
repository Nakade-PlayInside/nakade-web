<?php
namespace League\Mail;

/**
 * mail for both players after auto result
 *
 * @package League\Mail
 */
class AutoResultMail extends LeagueMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        //todo: email body
        $message =
            $this->translate('A new appointment for your match date at %URL% was set.') . ' ' .
            $this->translate('The date was edited by a league manager on request.') .
            PHP_EOL . PHP_EOL .
            $this->translate('Your match: %MATCH_INFO%') .
            PHP_EOL .
            $this->translate('Actual match date: %OLD_DATE%') .
            PHP_EOL .
            $this->translate('New match date: %NEW_DATE%') .
            PHP_EOL . PHP_EOL .
            $this->translate('This new date is binding even without confirming.') . ' ' .
            $this->translate('If you use a calendar application, do not forget to update.') . ' ' .
            $this->translate('An updated iCal is found on your site after login at %URL%.') .
            PHP_EOL .
            $this->translate('In case of a mistake, please contact a league manager.') .
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
        return $this->translate('Your Match Result');
    }


}