<?php
namespace User\Form;

use Zend\InputFilter\InputFilter;

class UserFilter extends InputFilter
{
    
    public function __construct()
    {
        $this->add(
             array(
                 'name' => 'title',
                 'required' => false,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                     array('name' => 'StripNewLines'),
                  ),
                 'validators' => array(
                     array('name'    => 'StringLength',
                           'options' => array (
                                  'encoding' => 'UTF-8', 
                                  'max'  => '10',
                           )  
                     ),  
                  )
              )
         );
        
  
         $this->add(
             array(
                 'name' => 'firstname',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                     array('name' => 'StripNewLines'),
                  ),
                 'validators' => array(
                     array('name'    => 'StringLength',
                           'options' => array (
                                  'encoding' => 'UTF-8', 
                                  'max'  => '20',
                           )  
                     ),  
                  )
              )
         );
         
         $this->add(
             array(
                 'name' => 'lastname',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                     array('name' => 'StripNewLines'),
                  ),
                 'validators' => array(
                     array('name'    => 'StringLength',
                           'options' => array (
                                  'encoding' => 'UTF-8', 
                                  'max'  => '30',
                           )  
                     ),  
                  )
              )
         );
        
         $this->add(
             array(
                 'name' => 'nickname',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                     array('name' => 'StripNewLines'),
                  ),
                 'validators' => array(
                     array('name'    => 'StringLength',
                           'options' => array (
                                  'encoding' => 'UTF-8', 
                                  'max'  => '20',
                           )  
                     ),
                     array(
                        'name'     => 'User\Form\Validator\DBNoRecordExist',
                        'options' => array( 
                            'entity'    => 'User\Entity\User',
                            'property' => 'nickname',
                            'adapter'  => $this->getEntityManager(),
                        )
                     )
                  )
              )
         );
         
         $this->add(
             array(
                 'name' => 'username',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                     array('name' => 'StripNewLines'),
                  ),
                 'validators' => array(
                     array('name'    => 'StringLength',
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
             )
         );
         
         $this->add(
             array(
                 'name' => 'password',
                 'required' => false,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                     array('name' => 'StripNewLines'),
                  ),
                 'validators' => array(
                     array('name'    => 'StringLength',
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
             )
         );
        
         $this->add(
             array(
                 'name' => 'email',
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
             )
         );
        
    }
}
