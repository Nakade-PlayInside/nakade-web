<?php
namespace League\Form;

/**
 * Form for making a new result
 */
class ResultForm extends AbstractTranslatorForm
{
   
    protected $_pairings;
    protected $_resultlist;
    protected $_id;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct($name ='ResultForm');
        
    } 
    
    /**
     * get pairing
     * @return array
     */
    public function getPairing() 
    {
        return $this->_pairings;
    }
            
    protected function getPlayerName($user)
    {
        $player = sprintf("%s %s ",
                      $user->getFirstName(),
                      $user->getLastName()
                  );
        
        
        if(!is_null($nick=$user->getNickname()))
            $player .= "($nick)";
        
        return $player;
    }        
    /**
     * set pairing
     * @param Match $pairing 
     */
    public function setPairing($pairing) 
    {
        $black = $this->getPlayerName($pairing->getBlack());
        $white = $this->getPlayerName($pairing->getWhite());
        
        $match = array(
            $pairing->getBlackId() => $black,
            $pairing->getWhiteId() => $white,
        );
        
        $this->_pairings=$match;
    }
    
    /**
     * get result list
     * @return array
     */
    public function getResultlist() 
    {
        return $this->_resultlist;
    }
    
    /**
     * set resultlist
     * @param type $resultlist
     */
    public function setResultlist($resultlist) 
    {
        
        $this->_resultlist=$resultlist;
    }
    
    /**
     * get id
     * @return int
     */
    public function getId() 
    {
        return $this->_id;
    }
    
    /**
     * set id
     * @param int $pairingId
     */
    public function setId($pairingId) 
    {
        $this->_id=$pairingId;
    }
    
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() 
    {
        $this->setAttribute('method', 'post');
        
        //pairingId
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Hidden',
                'name' => 'pid',
                'attributes' => array(
                    'value' => $this->getId()
                ),
            )
        );
       
        //winner
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'winner',
                'options' => array(
                    'label' => $this->translate('Winner'),
                    'empty_option' => $this->translate('No Winner'),
                    'value_options' => $this->getPairing()
                ),
            )
        );
        
        //result
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'result',
                'options' => array(
                    'label' => $this->translate('Result'),
                    'empty_option' => $this->translate('No Result'),
                    'value_options' => $this->getResultlist(),
                ),
            )
         );
        
         //points
         $this->add(
             array(
                 'name' => 'points',
                 'attributes' => array(
                    'type' => 'text',
                  //  'pattern' => "[12]{0,1}[0-9]{1}([.][5]){0,1}"
                 ),
                 'options'  => array(
                    'label' => $this->translate('Points')
                 ),
            )
        );
        
         //submit
        $this->add(
            array(
                'name' => 'submit',
                'attributes' => array(
                    'type'  => 'submit',
                    'value' => $this->translate('Go'),
                    'id' => 'submitbutton',
                ),
            )
        );
        
    }
}
?>
