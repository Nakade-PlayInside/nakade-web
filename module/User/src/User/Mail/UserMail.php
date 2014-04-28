<?php
namespace User\Mail;

use Nakade\Abstracts\AbstractTranslation;
use Zend\Mail\Transport\TransportInterface;
use Mail\Services\MailMessageFactory;

/**
 * Class UserMail
 *
 * @package User\Mail
 */
abstract class UserMail extends AbstractTranslation implements UserMailInterface
{
    /* @var $transport TransportInterface */
    protected $transport;

    /* @var $message MailMessageFactory */
    protected $message;

    protected $recipient;
    protected $recipientName;

    /**
     * properties
     * @var mixed
     */
    protected $firstname;
    protected $username;
    protected $password;
    protected $verifyString;
    protected $verifyUrl="http://www.nakade.de";
    protected $activationLink;


    /**
     * used placeholder vars
     * @var array
     */
    protected $mailVariables = array(
        'firstname' => 'firstname',
        'username'  => 'username',
        'password'  => 'password',
        'expire'    => 'expire',
        'signature' => 'signature',
        'club'      => 'club',
        'register_court'  => 'register_court',
        'register_no'      => 'register_no',
        'activationLink'   => 'activationLink',
        'prefix'    => 'prefix',
    );

    /**
     * mail template
     *
     * @var array
     */
    protected $mailTemplates = array(
        self::SALUTATION => "Welcome %firstname%,\n\nThank you for your registration at nakade.de\n\n",
        self::ACCOUNT    => "Your Credentials:\n\nusername: %username%\npassword: %password%\n\n",
        self::VERIFY     => "\nYour account requires activation during the next %expire% hours. Please click on the link for activation. If you fail to activate your account in time, you have to reset your password using the \"forgot password\" option.\n",
        self::GREETING   => "\nMay the stones be with you\n\nYour %signature%\n",
        self::CONTACT    => "\n\n%club%\nCourt of Registration: %register_court%\nRegister No.: %register_no%",
        self::LINK       => "\n\n%activationLink%\n\n",
        self::SUBJECT    => "%prefix% - Your Credentials",
    );


    /**
     * @param MailMessageFactory $message
     * @param TransportInterface $transport
     */
    public function __construct(MailMessageFactory $message, TransportInterface $transport)
    {
        $this->transport=$transport;
        $this->message=$message;
    }

    /**
     * options :
     *  - expire -> hours
     *  - prefix -> subject prefix
     *  - signature -> email signature
     *  - club -> contact name
     *  - register_court => Amtsgericht
     *  - register_no    => Registriernummer
     *
     * @param array $options
     */
    public function setEmailOptions(array $options=null)
    {

        foreach ($options as $name => $option) {
            if (property_exists($this, $name)) {
                $this->$name = $option;
            }
        }

    }

    /**
     * set the recipient
     *
     * @param string $email
     * @param string $name
     */
    public function setRecipient($email, $name=null)
    {
        $this->recipient = $email;

        if (!empty($name)) {
            $this->recipientName = $name;
        }
    }

    /**
     * Send mail to the given email address.
     * The recipient has to be prior to sending.
     * name of the recipient is an optional string.
     *
     * @return bool
     */
    public function send()
    {
        $body = $this->getBody();
        $subject = $this->getSubject();

        $this->message->setBody($body);
        $this->message->setSubject($subject);
        $this->message->setTo($this->recipient);
        $this->message->setToName($this->recipientName);

        $message = $this->message->getMessage();

        try {

            $this->transport->send($message);
            return true;

        } catch (\Exception $e) {

            return false;
        }


    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {

        $this->verifyUrl = isset($data['verifyUrl']) ?
            $data['verifyUrl'] : null;

        $this->verifyString = isset($data['verifyString']) ?
            $data['verifyString'] : null;

        $this->firstname = isset($data['firstname'])? $data['firstname'] : null;
        $this->username  = isset($data['username']) ? $data['username'] : null;
        $this->password  = isset($data['generated']) ? $data['generated'] : null;

        $email = isset($data['email']) ? $data['email'] : null;
        $this->activationLink = sprintf('%s/verify?email=%s&verify=%s',
            $this->verifyUrl,
            $email,
            $this->verifyString
        );

        return $this;
    }

    /**
     * @return string
     */
    abstract public function getTranslatedMailTemplate();

    /**
     * @return string
     */
    public function getSubject()
    {
        $temp    = $this->mailTemplates[self::SUBJECT];
        $subject = $this->translate($temp);

        return $this->setPlaceholder($subject);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        $template    = $this->getTranslatedMailTemplate();
        $translation = $this->createMail($template);

        return $translation;
    }

    /**
     * Replaces all placeholder with property values.
     *
     * @param string $message
     *
     * @return string
     */
    protected function setPlaceholder($message)
    {
        foreach ($this->mailVariables as $ident => $property) {
            $value = $this->$property;
            $message = str_replace("%$ident%", (string) $value, $message);
        }

        return $message;
    }

    /**
     * Composing the mail content by templates
     *
     * @param array $template
     *
     * @return string
     */
    protected function createMail(array $template=null)
    {

        if (is_null($template)) {
            $template = $this->mailTemplates;
        }


        $message  = "";
        $message .= $template[self::SALUTATION];
        $message .= $template[self::ACCOUNT];
        $message .= $template[self::VERIFY];
        $message .= $template[self::LINK];
        $message .= $template[self::GREETING];
        $message .= $template[self::CONTACT];

        return $this->setPlaceholder($message);

    }


}

