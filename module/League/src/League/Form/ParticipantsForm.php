<?php
namespace League\Form;

class ParticipantsForm extends AbstractTranslatorForm
{
   
    protected $_players;
    protected $_selectedPlayers;
    protected $_sid;
    protected $_leagues;
    
    /**
     * Constructor
     */        
    public function __construct()
    {
         //form name is AuthForm
        parent::__construct($name='PlayersForm');
    } 
    
    /**
     * set players as an array
     * @param array $players
     * @return \League\Form\ParticipantsForm
     */
    public function setPlayers(array $players) 
    {
        $this->_players = $players;
        return $this;
    } 
    
    /**
     * get players
     * @return array
     */
    public function getPlayers() 
    {
        return $this->_players;
    }
    
    /**
     * set players to select. An array of id is neccessary.
     * @param array $players
     * @return \League\Form\ParticipantsForm
     */
    public function setSelectedPlayers(array $players) 
    {
        $this->_selectedPlayers = $players;
        return $this;
    } 
    
    /**
     * get selected players. If not set all players are selected 
     * @return array
     */
    public function getSelectedPlayers() 
    {
        if($this->_selectedPlayers==null) {
             $this->_selectedPlayers = array_keys($this->_players);
        } 
        return $this->_selectedPlayers;
    }
    
    /**
     * set leagues 
     * @param array $leagues
     * @return \League\Form\ParticipantsForm
     */
    public function setLeagues(array $leagues) 
    {
        $this->_leagues = $leagues;
        return $this;
    } 
    
    /**
     * get leagues
     * @return array
     */
    public function getLeagues() 
    {
        return $this->_leagues;
    }
    
    /**
     * set seasonId
     * @param int $sid
     * @return \League\Form\ParticipantsForm
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
       
        
        //leagues
        $this->add(
            array(
                'name' => 'league',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('League No:'),
                    'value_options' => $this->getLeagues(),
                )
                
            )
        );
        
        //players
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'player',
            'attributes' => array(
                'value'  => $this->getSelectedPlayers(),
                'size'   => '6',
                'multiple' => 'multiple',
            ),
            'options' => array(
                'label' => $this->translate('Players'),
                'value_options' => $this->getPlayers(),
            ),
        ));
        
        //season ID
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Hidden',
                'name' => 'sid',
                'attributes' => array(
                    'value'  => $this->getSeasonId(),
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
