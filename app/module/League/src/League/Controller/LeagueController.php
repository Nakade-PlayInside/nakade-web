<?php
namespace League\Controller;

use Zend\Form\FormInterface;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * Manages a round-robin league. Registered players are connected to the 
 * User Module. The league contains league tables with tie-breakers and is part
 * of a season. The pairings have a given date to play and collecting the 
 * results. The ruleset decides which tie-breakers are used for the placement.
 * 
 * @author Holger Maerz <holger@nakade.de>
 */
class LeagueController extends AbstractController
{
   
    public function indexAction()
    {
      
      
       //better to get the last season 
      // $actualSeason =  $this->getSeasonMapperService()->getActualSeason();
       //$league       =  $this->league()->getTopLeague($actualSeason);
        
       return new ViewModel(
           array(
              //'users' => $this->table()->getTable($league),
              //'nextGames' => $this->match()->getNextThreeGames($league),
           )
       );
    }
    
    
    public function addAction()
    {
       $league = $this->getService()->getNewLeague();
      
       if(null===$league) {
            $this->redirect()->toRoute('newseason/add');
       } 
     
       $form = $this->getForm('league');
       $form->bindEntity($league);
       
       if ($this->getRequest()->isPost()) {
            
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if($postData['cancel']) {
                return $this->redirect()->toRoute('newseason');
            }
            
            $form->setData($postData);
           
            if ($form->isValid()) {
           
                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $this->getService()->addLeague($data);
                
                return $this->redirect()->toRoute('newseason');
            }
        }

        
        return new ViewModel(
           array(
              'form' => $form,
           )
        );
    }

    
    
}
