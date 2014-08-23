<?php
namespace League\Mail;

/**
 * mail for both players
 *
 * @package League\Mail
 */
class ResultEditMail extends LeagueMail
{

    /**
     * @return string
     */
    public function getMailBody()
    {

        $message =
            $this->translate('The result of your match was edited by your league manager on request.') .
            PHP_EOL . PHP_EOL .
            $this->translate('Your match: %MATCH_INFO%') .
            PHP_EOL .
            $this->translate('Result: %RESULT%') .
            PHP_EOL . PHP_EOL .
            $this->translate('In case of a mistake, please contact your league manager.') .
            PHP_EOL . PHP_EOL .
            $this->getSignature()->getSignatureText();

            $this->makeReplacements($message);

        return $message;
    }

    /**
     * @return string
     */
    public  function getSubject()
    {
        return $this->translate('Your Match Result was edited');
    }

}