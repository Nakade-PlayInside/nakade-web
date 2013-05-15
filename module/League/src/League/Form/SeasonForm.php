<?php
namespace League\Form;
use \DateTime;

/**
 * Form for making a new season
 */
class SeasonForm extends AbstractTranslatorForm
{
   
    protected $_startDate;
    protected $_minDate;
    protected $_maxDate;
    protected $_title='Bundesliga';
    protected $_number=1;
    
    /**
     * constructor 
     */        
    public function __construct()
    {
        //form name is SeasonForm
        parent::__construct($name='SeasonForm');
        
        $today=new \DateTime();
        $this->_minDate   = $today->format('Y-m-d');
        $this->_startDate = $today->modify('+2 week')->format('Y-m-d');
        $this->_maxDate   = $today->modify('+2 year')->format('Y-m-d');
        
    } 
    
    /**
     * set season number
     * @param int $number
     * @return \League\Form\SeasonForm
     */
    public function setNumber($number=1) 
    {
        $this->_number = $number;
        return $this;
    } 
    
    /**
     * get season number
     * @return int
     */
    public function getNumber() 
    {
        return $this->_number;
    }  
    
    /**
     * set season title
     * @param string $title
     * @return \League\Form\SeasonForm
     */
    public function setTitle($title='Bundesliga') 
    {
        $this->_title = $title;
        return $this;
    } 
    
    /**
     * get season title
     * @return string
     */
    public function getTitle() 
    {
        return $this->_title;
    }
    
    /**
     * set date. In detail start, minimum and maximum date
     * @param DateTime $year
     * @return \League\Form\SeasonForm
     */
    public function setDate(DateTime $year) 
    {
        $this->_startDate = $year->format('Y-m-d');
        $this->_minDate   = $year->modify('-2 week')->format('Y-m-d');
        $this->_maxDate   = $year->modify('+1 year')->format('Y-m-d');
        
        return $this;
    } 
    
    /**
     * get starting date
     * @return DateTime
     */
    public function getStartDate() 
    {
        return $this->_startDate;
    }
    
    /**
     * get minimum date
     * @return DateTime
     */
    public function getMinDate() 
    {
        return $this->_minDate;
    }
    
    /**
     * get maximum date
     * @return DateTime
     */
    public function getMaxDate() 
    {
        return $this->_maxDate;
    }
    
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
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
                    'label' => $this->translate('Season Start'),
                    'format' => 'Y-m-d',
                 ),
                'attributes' => array(
                    'min'   => $this->_minDate,
                    'max'   => $this->_maxDate,
                    'step'  => '1', // days; default step interval is 1 day
                    'value' => $this->_startDate,
                )
            )
        );
        
        //number
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Hidden',
                'name' => 'number',
                'attributes' => array(
                    'value'  => $this->_number,
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
