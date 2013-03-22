<?php

namespace Authentication\Services;

use Traversable;
use Authentication\Form\ContactFilter;
use Authentication\Form\MyAuthForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class AuthFormFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
      
        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        
        //$name    = $config['phly_contact']['form']['name'];
        //$captcha = $services->get('PhlyContactCaptcha');
        $translator = $services->get('translator');
        //$filter  = new ContactFilter();
       
  //    echo "<pre>";
   //   var_dump($textDomain);exit;
        
        $form    = new MyAuthForm($translator);
       // $form->setInputFilter($filter);
        return $form;
    }
}
