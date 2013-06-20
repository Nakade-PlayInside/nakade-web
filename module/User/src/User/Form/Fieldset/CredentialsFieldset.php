<?php
namespace User\Form\Fieldset;

use \Zend\InputFilter\InputFilterProviderInterface;
use User\Form\Fieldset\AbstractFieldset;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;


/**
 * Form for adding a new User
 */
class CredentialsFieldset extends AbstractFieldset 
        implements InputFilterProviderInterface
        
{
  
    /**
     * Constructor
     */        
    public function __construct($em)
    {
        $this->_entity_manager = $em;
        
        //form name is LeagueForm
        parent::__construct('credentials');
        $this->setHydrator(new Hydrator());
        $this->prepare();
    } 
   
    
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    protected function prepare() {
       
        $this->setLabel($this->translate("Credentials"));
        
        //User name
        $this->add(
            array(
                'name' => 'username',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('User Name:'),
                ),
                'attributes' => array(
                    'required' => 'required',
                   
                )
            )
        );
        
        //password
        $this->add(
            array(
                'name' => 'password',
                'type' => 'Zend\Form\Element\Password',
                'options' => array(
                    'label' =>  $this->translate('Password (generated if empty):'),
                    
                ),
                
            )
        );
        
        //email
        $this->add(
            array(
                'name' => 'email',
                'type' => 'Zend\Form\Element\Email',
                'options' => array(
                    'label' =>  $this->translate('email:'),
                    
                ),
                'attributes' => array(
                    'multiple' => false,
                    'required' => 'required',
                )
            )
        );
        
       
    } 
    
    public function getInputFilterSpecification() {
        
        return array(
           'username' => array(
               'required' => true,
               'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                    ),
                'validators' => array(
                    array('name' => 'StringLength',
                          'options' => array (
                              'encoding' => 'UTF-8', 
                              'min'  => '6',
                              'max'  => '50',
                          )    
                    ),
                    array('name' => 'Regex',
                          'break_chain_on_failure' => true,
                          'options' => array(
                              'pattern' => '/^[a-zA-Z0-9_@\.]+$/',
                              'message' => $this->translate('allowed: a-Z0-9_.@')
                          )
                    ),
                    array(
                        'name'     => 'User\Form\Validator\DBNoRecordExist',
                        'options' => array( 
                            'entity'    => 'User\Entity\User',
                            'property'  => 'username',
                            'adapter'  => $this->getEntityManager(),
                        )
                    )
                 )   
            ),
            'password' => array(
               'required' => false,
               'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                    ),
                'validators' => array(
                    array('name' => 'StringLength',
                          'break_chain_on_failure' => true,
                          'options' => array (
                              'encoding' => 'UTF-8', 
                              'min'  => '6',
                              'max'  => '50',
                          )  
                     ),
                    array('name' => 'User\Form\Validator\PasswordComplexity',
                          'break_chain_on_failure' => true,
                          'options' => array (
                              'length'   => '8',
                              'treshold' => '80',
                          )
                    ),
                    array('name' => 'User\Form\Validator\CommonPassword',
                          'break_chain_on_failure' => true,
                          'options' => array (  
                               'commons'  => array(
                                                'password',
                                                '123456',
                                                'qwert',
                                                'abc123',
                                                'letmein',
                                                'myspace',
                                                'monkey',
                                                'iloveyou',
                                                'sunshine',
                                                'trustno1',
                                                'welcome',
                                                'shadow',
                              )
                         )       
                    ),
                )        
            ),
            'email'=> array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                    ),
                'validators' => array(
                    array('name' => 'StringLength',
                          'options' => array (
                              'encoding' => 'UTF-8', 
                              'min'  => '6',
                              'max'  => '120',
                          )  
                    ),
                    array('name' => 'EmailAddress',
                          'break_chain_on_failure' => true,
                    ),
                    array(
                        'name'     => 'User\Form\Validator\DBNoRecordExist',
                        'options' => array( 
                            'entity'    => 'User\Entity\User',
                            'property'  => 'email',
                            'adapter'  => $this->getEntityManager(),
                        )
                    )
                 )   
            ),
        );
    }
}
?>
