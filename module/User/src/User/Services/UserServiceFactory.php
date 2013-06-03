<?php
namespace User\Services;


use User\Mapper\UserMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;


/**
 * Factory for creating the Zend Authentication Service. Using customized
 * Adapter and Storage instances. 
 * 
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class UserServiceFactory 
    extends AbstractTranslationService 
    implements FactoryInterface 
{
   
    protected $_user_mapper;
    protected $_form;
    
    
    public function getUserMapper() 
    {
        return $this->_user_mapper;
    }
    
    public function setUserMapper($mapper) 
    {
        $this->_user_mapper=$mapper;
        return $this;
    }
    
    public function getForm() 
    {
        return $this->_form;
    }
    
    public function setForm($form) 
    {
        $this->_form=$form;
        return $this;
    }
    /**
     * Creating Zend Authentication Service for logIn and logOut action.
     * Making use of customized adapters for more action as by default.
     * Integration of optional translation feature (i18N)
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return \Zend\Authentication\AuthenticationService
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
        $textDomain = isset($config['League']['text_domain']) ? 
            $config['League']['text_domain'] : null;
         
        //EntityManager for database access by doctrine
        $entityManager = $services->get('Doctrine\ORM\EntityManager');
        
        if (null === $entityManager) {
            throw new RuntimeException(
                sprintf('Entity manager could not be found in service.')
            );
        }
        
        //optional translator
        $translator = $services->get('translator');
        $this->setTranslator($translator, $textDomain);
        
        $this->_user_mapper = new UserMapper($entityManager);
        $this->_form = $services->get('user_form');
        
        
        return $this;
        
    }
    
   
    /**
     * shows all open results in a season.
     * A link is provided for each match to enter a result.
     * @return mixed
     */
    public function getAllUser() {
       
        return $this->getUserMapper()->getAllUser();
        
    }
    
    /**
     * Get the title for open results.
     * If no season is found it will return a not found information.
     * 
     * @return string
     */
    public function getOpenResultTitle() {
       
       $infos=$this->getSeasonMapper()->getActualSeasonInfos();
       if($infos==null)
           return $this->translate("No ongoing season found.");
       
       return sprintf(
                  "%s %s %02d/%d",
                  $this->translate('Open Results'),
                  $this->translate('Season'),
                  $infos['number'], 
                  date_format($infos['year'], 'y')
              );
    }
   
    public function prepareFormForValidation($form, $data)
    {
        $form->setData($data);
            
        //set filter and validator dependant on values 
        $this->setResultFormValidators($form);
        
        
    }
    
    /**
     * Returns a result form.
     * Before returning it, match data and the ID are set.
     *  
     * @param int $pid
     * @return Form
     */
    public function setResultFormValues($pid) {
       
       //match to enter a result
        $this->_match = $this->getMatchMapper()->getMatchById($pid);
        
        //form
        $form = $this->getResultForm();
        $form->setPairing($this->_match);
        $form->setId($pid);
        $form->init();
        $this->setResultForm($form);
             
        return $form;
    }
   
    /**
     * Activates the filter and validator for points 
     * or winner in the form provided dependant on the result input.
     * This has to be set before validation. 
     * 
     * @param type $form
     */
    public function setResultFormValidators(&$form)
    {
            $result = $form->get('result')->getValue();
            
            switch($result) {
            
                case RESULT::BYPOINTS: 
                    
                        $filter  = new PointsFilter();
                        break;
                    
                case RESULT::DRAW:
                case RESULT::SUSPENDED: 
                    
                        $filter  = new WinnerFilter(); 
                        break;
                    
                default: return;    
            }
            
            $form->setInputFilter($filter);
            
            
    }        
    
    
    /**
     * processing and save match data to database
     *  
     * @param Form $form
     * @return boolean 
     */
    public function processResultData($form)
    {
        
        if($form->isValid()) {
            
            $validatedData = $form->getData();
            $this->filterResultWinner($validatedData);
            $this->filterResultPoints($validatedData);
        
            $match = $this->getMatchMapper()->getMatchById($validatedData['pid']);
            $match->populate($validatedData);
        
            $this->getMatchMapper()->save($match);
            return true;
        }
        
        return false;
    }
    
    /**
     * unset the winner if the match is a draw or suspended
     * @param array $validatedData
     */
    protected function filterResultWinner(&$validatedData)
    {
        switch($validatedData['result']) {
            
           case RESULT::DRAW: 
           case RESULT::SUSPENDED:  
                    $validatedData['winner']=null;
                    
        }
    }
    
    /**
     * Unset points if result is not by points. If the offset
     * points are exceeded, result is set to resignation.
     *  
     * @param array $validatedData
     */
    protected function filterResultPoints(&$validatedData)
    {
        
        switch($validatedData['result']) {
            
           case RESULT::BYPOINTS:
               
               if($validatedData['points']>=HAHN::OFFSET_POINTS) {
                   $validatedData['points']='';
                   $validatedData['result']=1;
               }
               break;
               
           default: 
               
               $validatedData['points']='';
               break;
        }
        
    }
    
}


