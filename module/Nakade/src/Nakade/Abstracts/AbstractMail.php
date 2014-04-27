<?php
namespace Nakade\Abstracts;

use Nakade\Abstracts\AbstractTranslation;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime;
use Zend\Validator\Exception\InvalidArgumentException;
use \Mail\Services\MailMessageFactory;

/**
 * Extending for having the mail transport system and default message body.
 * In addition an optional translator can be set
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
abstract class AbstractMail extends AbstractTranslation
{

    protected $_transport;
    protected $_message;
    protected $_mailVariables;
    protected $_mailTemplates;
    protected $_recipientEmail;
    protected $_recipientName;

    /**
     * set a mail transport. returns a fluent interface
     *
     * @param \Zend\Mail\Transport\TransportInterface $transport
     *
     * @return $this
     */
    public function setTransport(TransportInterface $transport)
    {
        $this->_transport=$transport;
        return $this;
    }

    /**
     * get mail transport or null
     *
     * @return mixed
     */
    public function getTransport()
    {
        return $this->_transport;
    }


    /**
     * set a mail body. returns a fluent interface
     *
     * @param MailMessageFactory $message
     *
     * @return $this
     */
    public function setMessage(MailMessageFactory $message)
    {
        $this->_message=$message;
        return $this;
    }

    /**
     * get mail body or null
     *
     * @return MailMessageFactory
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * returns a plain text mime part message
     *
     * @param string $rawbody
     *
     * @return \Zend\Mime\Part
     */
    protected function getTextMessage($rawbody)
    {
         $text = new Mime\Part($rawbody);
         $text->type = Mime\Mime::TYPE_TEXT;

         return $text;
    }

    /**
     * returns a HTML/Text mime part message.
     * New line is transformed to HTML br tag
     *
     * @param string $rawbody
     *
     * @return \Zend\Mime\Part
     */
    protected function getHTMLMessage($rawbody)
    {
         $temp = nl2br($rawbody);

         $html = new Mime\Part($temp);
         $html->type = Mime\Mime::TYPE_HTML;
         $html->charset = 'utf-8';

         return $html;
    }


    /**
     * implement for getting a mail subject
     */
    abstract public function getSubject();

    /**
     * implement for getting a mail body
     */
    abstract public function getBody();

    /**
     * set the recipient
     *
     * @param string $email
     * @param string $name
     */
    public function setRecipient($email, $name=null)
    {
        $this->_recipientEmail = $email;

        if (isset($name)) {
            $this->_recipientName = $name;
        }
    }

    /**
     * Send mail to the given email adress.
     * The recipent has to be prior to sending.
     * name of the recipient is an optional string.
     */
    public function send()
    {
         $body = $this->getBody();
         $subject = $this->getSubject();

         $this->getMessage()->setBody($body);
         $this->getMessage()->setSubject($subject);
         $this->getMessage()->setTo($this->_recipientEmail);
         $this->getMessage()->setToName($this->_recipientName);

         $message = $this->getMessage()->getMessage();
         $this->getTransport()->send($message);
    }

     /**
     * Magic function returns the value of the requested property, if and only if it is the value or a
     * message variable.
     *
     * @param string $property
      *
     * @return mixed
      *
     * @throws InvalidArgumentException
     */
    public function __get($property)
    {
        if (is_null($property)) {
            throw new InvalidArgumentException("Property is by the name '$property' is null");
        }

        if (array_key_exists($property, $this->_mailVariables)) {
            return $this->_mailVariables[$property];
        }

        throw new InvalidArgumentException("No property exists by the name '$property'");
    }

}
