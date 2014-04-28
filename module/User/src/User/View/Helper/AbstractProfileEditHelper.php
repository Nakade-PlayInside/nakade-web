<?php
namespace User\View\Helper;

use User\Entity\User;
use Nakade\Abstracts\AbstractViewHelper;
/**
 * Extending for having a helper for profile editing.
 * Specifically for the profile editing view
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
abstract class AbstractProfileEditHelper extends AbstractViewHelper
{

    const LINK        = 'link';
    const METHOD      = 'method';
    const ANONYMOUS   = 'anonym';
    const ANONYM_IMG  = 'anonymousImg';
    const PWD_NEVER   = 'pwdNeverChanged';
    const PWD_CHANGE  = 'pwdChangeInfo';

    const CSS_LINK    = "cssLinkStyle";
    const CSS_VALUE   = "cssValueStyle;";
    const CSS_EDIT    = "cssEditStyle";
    const CSS_PWD     = "cssPwdStyle";
    const CSS_ANONYM  = "cssAnonymStyle";

    protected $_vars = array();
    protected $_url;

    /**
     * @var array
     */
    protected $_templates = array(
        self::METHOD     => "edit",
        self::ANONYMOUS  => "Incognito",
        self::PWD_NEVER  => "password was never changed",
        self::PWD_CHANGE => "last time edited on %pwdChangeDate%",

        self::ANONYM_IMG    => "/images/anonymous.png",
        self::CSS_LINK      => "display:block; cursor:pointer; text-decoration:none; background-color:transparent; width:100%",
        self::CSS_VALUE     => "color:#333333;",
        self::CSS_EDIT      => "padding-left:30px; float:right;",
        self::CSS_PWD       => "color:grey;color: hsl(240,10%,65%);",
        self::CSS_ANONYM    => "vertical-align:middle; margin-left:10px;",
    );


    /**
     * Returns a translated message for password change information.
     * You have to provide a constant string for the message:
     *    - PWD_NEVER -> for getting the information pwd was never changed
     *    - PWD_CHANGE ->for the last time the pwd was edited
     *
     * @param string $type
     *
     * @return null|string
     */
    public function getMessage($type)
    {
        $template = $this->getTemplate();
        if (array_key_exists($type, $template)) {
           return $this->replaceTemplateVars($template[$type]);
        }

        return null;
    }

    /**
     * get translated template array
     *
     * @return array
     */
    protected function getTemplate()
    {
         $template = array(

            self::METHOD     => $this->translate("edit"),
            self::ANONYMOUS  => $this->translate("Incognito"),
            self::PWD_NEVER  => $this->translate("password was never changed"),
            self::PWD_CHANGE =>
                 $this->translate("last time edited on %pwdChangeDate%"),
            self::LINK => $this->_templates[self::LINK],

        );

        return $template;
    }

    /**
     * Replaces all placeholder with property values.
     *
     * @param string $message
     *
     * @return string
     */
    protected function replaceTemplateVars($message)
    {
        $vars = $this->_vars;

        foreach ($vars as $ident => $property) {
            $value = $this->$property;
            $message = str_replace("%$ident%", (string) $value, $message);
        }

        return $message;
    }


    /**
     * Change the css style. Expecting an existing style as a constant.
     * Use this to change the password information style
     *
     * @param string $type
     * @param string $style
     */
    public function setStyle($type, $style)
    {
        if (array_key_exists($type, $this->_templates) &&
           array_key_exists($style, $this->_templates)
        ) {
            $this->_templates[$type]=$this->_templates[$style];
        }
    }

    /**
     * Get the link with its value in the middle, the editing
     * information and the forwarded url
     *
     * @param string $value
     *
     * @return string
     */
    public function getLink($value)
    {
         $placeholder = array (
            'method'   => $this->getMessage(self::METHOD),
            'url'      => $this->_url,
            'cssLink'  => $this->_templates[self::CSS_LINK],
            'cssValue' => $this->_templates[self::CSS_VALUE],
            'cssEdit'  => $this->_templates[self::CSS_EDIT],

         );

         $link  = '<a href="%url%" title="%method%" style="%cssLink%">';
         $link .= '<span style="%cssValue%">'.$value.'</span>';
         $link .= '<span style="%cssEdit%">%method%</span>';
         $link .= '</a>';

         return $this->setPlaceholders($link, $placeholder);

    }

    /**
     * get an image for indicating anonymous state of a nickname
     *
     * @return string
     */
    public function getAnonymousImage()
    {
        $placeholder = array (
            'title'    => $this->getMessage(self::ANONYMOUS),
            'cssAnonym'=> $this->_templates[self::CSS_ANONYM],
            'image'    => $this->_templates[self::ANONYM_IMG],
         );

        $img  = '<img style="%cssAnonym%" title="%title%" '. 'alt="%title%" src="%image%" />';

        return  $this->setPlaceholders($img, $placeholder);
    }

    /**
     * @param User $profile
     *
     * @return mixed
     */
    abstract public function __invoke(User $profile);
}

