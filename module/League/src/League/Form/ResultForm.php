<?php
namespace League\Form;

use Zend\Form\Form;


class ResultForm extends Form
{
   
    public function __construct($pairing, $resultlist, $name = null)
    {
        
        $result =array();
        $result['-1'] = 'kein Ergebnis';
        foreach($resultlist as $res){
            $result[$res->getId()] = $res->getResult();
            
        } 
        // we want to ignore the name passed
        parent::__construct('league');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => '_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'winner',
            'attributes' => array(
                'value'  => '-1',
            ),
            'options' => array(
                'label' => 'Sieger',
                'value_options' => array(
                   '-1' => 'kein Sieger',
                   $pairing->getBlackId() => $pairing->getBlack()->getFirstName(),
                   $pairing->getWhiteId() => $pairing->getWhite()->getFirstName(),
                )
                ,
            ),
        ));
         
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'result',
            'attributes' => array(
                'value'  => '-1',
            ),
            'options' => array(
                'label' => 'Ergebnis',
                'value_options' => $result,
            ),
        ));
         $this->add(array(
            'name' => 'points',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Punkte'
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    } 
}
?>
