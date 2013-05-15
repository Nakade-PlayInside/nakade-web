<?php
namespace League\Form;

/**
 * Form for making a new league
 */
class LeagueForm extends AbstractTranslatorForm
{
   
    protected $_sid;
    protected $_title='Top-Liga';
    protected $_number=1;
    
    /**
     * Constructor
     */        
    public function __construct()
    {
        //form name is LeagueForm
        parent::__construct($name='LeagueForm');
    } 
    
    /**
     * set number
     * @param int $number
     * @return \League\Form\LeagueForm
     */
    public function setNumber($number=1) 
    {
        $this->_number = $number;
        return $this;
    } 
    
    /**
     * get number
     * @return int
     */
    public function getNumber() 
    {
        return $this->_number;
    }  
    
    /**
     * set title
     * @param string $title
     * @return \League\Form\LeagueForm
     */
    public function setTitle($title='Top-Liga') 
    {
        $this->_title = $title;
        return $this;
    } 
    
    /**
     * get title
     * @return string
     */
    public function getTitle() 
    {
        return $this->_title;
    }
    
    /**
     * set seasonId
     * @param int $sid
     * @return \League\Form\LeagueForm
     */
    public function setSeasonId($sid) 
    {
        $this->_sid = $sid;
        return $this;
    } 
    
    /**
     * get seasonId
     * @return int
     */
    public function getSeasonId() 
    {
        return $this->_sid;
    }
    
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() {
       
        //number
        $this->add(
            array(
                'name' => 'number',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('No:'),
                ),
                'attributes' => array(
                    'value' => $this->_number,
                    'readonly'=> true
                )
            )
        );
        
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
        
        //season ID
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Hidden',
                'name' => 'sid',
                'attributes' => array(
                    'value'  => $this->_sid,
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
    
}
?>
