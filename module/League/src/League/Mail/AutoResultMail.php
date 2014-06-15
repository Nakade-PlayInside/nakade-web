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
        $message =
            $this->translate('Your match result was automatically set at %URL%.') .
            PHP_EOL . PHP_EOL .
            $this->translate('Your match: %MATCH_INFO%') .
            PHP_EOL .
            $this->translate('RESULT: Suspended') .
            PHP_EOL . PHP_EOL .
            $this->translate('According to our rules, a match is suspended if no result is provided 72h after match date.') . ' ' .
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