<?php
//module/Authentication/src/Authentication/Services/AuthServiceFactory.php
namespace League\Services;

use League\Form\WinnerFilter;
use League\Form\PointsFilter;
use League\Mapper\SeasonMapper;
use League\Mapper\MatchMapper;
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
class ResultServiceFactory 
    extends AbstractTranslationService 
    implements FactoryInterface 
{
   
    protected $_season_mapper;
    protected $_match_mapper;
    protected $_result_form;
    
    public function getSeasonMapper() 
    {
        return $this->_season_mapper;
    }
    
    public function setSeasonMapper($mapper) 
    {
        $this->_season_mapper=$mapper;
        return $this;
    }
    
    public function getResultForm() 
    {
        return $this->_result_form;
    }
    public function setResultForm($form) 
    {
        $this->_result_form=$form;
        return $this;
    }
    
    public function getMatchMapper() 
    {
        return $this->_match_mapper;
    }
    
    public function setMatchMapper($mapper) 
    {
        $this->_match_mapper=$mapper;
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
        
        $this->_season_mapper = new SeasonMapper($entityManager);
        $this->_match_mapper  = new MatchMapper($entityManager);
        $this->_result_form = $services->get('result_form');
      
        return $this;
        
    }
    
    public function getNotFoundInfo()
    {
        return $this->translate("No ongoing season found.");
    }        
    
    public function getOpenResult() {
       
        $season = $this->getSeasonMapper()->getActualSeason();
        if($season==null)
            return null;
        
        return $this->getMatchMapper()->getAllOpenResults($season->getId());
    }
    
    public function getOpenResultTitle() {
       
       
       $season = $this->getSeasonMapper()->getActualSeason();
       if($season==null)
           return $this->getNotFoundInfo ();
       
       return sprintf(
                  "%s %s %02d/%d",
                  $this->translate('Open Results'),
                  $this->translate('Season'),
                  $season->getNumber(), 
                  date_format($season->getYear(), 'y')
              );
    }
   
    public function getMatch($pid) {
       
       //match 
       return $this->getMatchMapper()->getMatchById($pid);
    }
    
    public function setResultFormValues($pid) {
       
       //match to enter a result
        $match= $this->getMatchMapper()->getMatchById($pid);
        
        //form
        $form = $this->getResultForm();
        $form->setPairing($match);
        $form->setId($pid);
        $form->init();
        $this->setResultForm($form);
                    
        return $form;
    }
   
    public function setResultFormValidators(&$form)
    {
            $result = $form->get('result')->getValue();
            
            if(2==$result) {
                $filter  = new PointsFilter();
                $form->setInputFilter($filter);
            }
            
            if(3==$result || 5==$result) {
                $filter  = new WinnerFilter();
                $form->setInputFilter($filter);
            }
    }        
    
    public function processResultData($validatedData)
    {
        
        $this->filterResultWinner($validatedData);
        $this->filterResultPoints($validatedData);
        
        $match = $this->getMatchMapper()->getMatchById($validatedData['pid']);
        $match->populate($validatedData);
        
       // $this->getMatchMapper()->save($match);
    }
    
    public function filterResultWinner(&$validatedData)
    {
        switch($validatedData['result']) {
            
           case 3: 
           case 5:  $validatedData['winner']=null;
                    break;
        }
    }
    
    public function filterResultPoints(&$validatedData)
    {
        if($validatedData['result']!=2) 
            $validatedData['points']='';
    }
    
}


