<?php
namespace Season\Mail;

/**
 * mail for both players after editing match date by an admin
 *
 * @package League\Mail
 */
class InvitationMail extends SeasonMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {
        //TODO
        $message =
            $this->translate('A new season is announced at %URL%.') . ' ' .
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
        return $this->translate('Invitation - New Nakade Season');
    }


}