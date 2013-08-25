<?php
namespace Message\Services;

use Nakade\Abstracts\AbstractService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;


/**
 * Factory for the actual season for receiving
 * sorted league tables and schedules.
 * 
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class MessageServiceFactory extends AbstractService
{
        
    /**
     * Actual Season Service for league tables and schedules.
     * Integration of optional translation feature (i18N)
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return ActualSeasonService
     * @throws RuntimeException
     * 
     */
    public function createService(ServiceLocatorInterface $services)
    {
      
        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        
        //configuration 
        $textDomain = isset($config['Message']['text_domain']) ? 
            $config['Message']['text_domain'] : null;
         
        
        $this->setMapperFactory($services->get('Message\Factory\MapperFactory'));
        
        //optional translator
        $translator = $services->get('translator');
        $this->setTranslator($translator, $textDomain);
      
        return $this;
        
    }
    
    /**
     
     * @return Messages
     */
    public function getMessages() 
    {
        
        $messages   = $this->getMapper('message')
                           ->getMessages();
        return $messages;
        
    }
    
   
   
 
}


