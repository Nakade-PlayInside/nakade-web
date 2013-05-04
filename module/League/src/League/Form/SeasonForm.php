<?php
namespace League\Form;

use Zend\Form\Form;

class SeasonForm extends Form
{
   
    protected $_translator;
    protected $_textDomain="League";
    protected $_today;
    protected $_title='Bundesliga';
    
            
    public function __construct($title=null)
    {
   
         //form name is AuthForm
        parent::__construct($name='SeasonForm');
       
        $today=new \DateTime();
        $this->_today=$today->format('Y-m-d');
         
        if(isset($title)) {
            
            $this->_title=$title;
        }
        
        
        $this->init();
        
    } 
    
    public function init() {
       
        //title
        $this->add(
            array(
                'name' => 'title',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Title:'),
                ),
                'attributes' => array(
                    'value' => $this->_title,
                )
            )
        );
        
        //date
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Date',
                'name' => 'year',
                'options' => array(
                    'label' => 'Season Start',
                    'format' => 'Y-m-d',
                    'value'  => '2013-10-12'
                ),
                'attributes' => array(
                    'min' => '2013-01-01',
                    'max' => '2023-01-01',
                    'step' => '1', // days; default step interval is 1 day
                    'value' => $this->_today,
                )
            )
        );
        
        //number
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Hidden',
                'name' => 'number',
                'attributes' => array(
                    'value'  => 3,
                )
            )
        );
        
        
        //cross-site scripting hash protection
        //this is handled by ZF2 in the background - no need for server-side 
        //validation 
        $this->add(
            array(
                'name' => 'csrf',
                'type'  => 'Zend\Form\Element\Csrf',
                'options' => array(
                    'csrf_options' => array(
                        'timeout' => 600
                    )
                )
            )    
        );
       
        //submit button
        $this->add(
            array(
                'name' => 'Send',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Submit'),

                ),
            )
        );
        
    } 
    
    /**
     * translator function. l18n
     * 
     * @param type $message
     * @return string $message
     */
    public function translate($message)
    {
        if (null === $this->_translator) {
           return $message;
        }
        
        return $this->_translator->translate(
                $message, 
                $this->_textDomain
        );
    }
    
    /**
     * Setter for translator in form. Enables the usage of i18N.
     * 
     * @param \Zend\I18n\Translator\Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->_translator = $translator;
    }
    
    /**
    * getter 
    * 
    * @return \Zend\I18n\Translator\Translator $translator
    */
    public function getTranslator()
    {
        return $this->_translator;
    }
    
}
?>
