<?php

namespace Application\Navigation;

use Zend\Navigation\Service\AbstractNavigationFactory;

/**
 * Class FooterNavigationFactory
 *
 * @package Application\Services
 */
class FooterNavigationFactory extends AbstractNavigationFactory
{

     public function getName()
     {
         return "siteMenu";
     }
}
