<?php
namespace League\Controller;

use Nakade\Controller\AbstractEntityManagerController;
use Zend\View\Model\ViewModel;
use League\Form\ResultForm;
use League\Helper\PositionCalculator;

/**
 * Manages a round-robin league. Registered players are connected to the 
 * User Module. The league contains league tables with tie-breakers and is part
 * of a season. The pairings have a given date to play and collecting the 
 * results. The ruleset decides which tie-breakers are used for the placement.
 * 
 * @author Holger Maerz <holger@nakade.de>
 */
class LeagueController extends AbstractEntityManagerController
{
    /**
    * viewhelper to exhibit the standings of the top league 
    */
    public function indexAction()
    {
        
       return new ViewModel(
           array(
              'users' => $this->getTopTable(),
              'nextGames' => $this->getNextThreeGames(),
           )
       );
    }
    
    public function comingNextAction()
    {
        
       return new ViewModel(
           array(
              'users' => $this->getNextGame()
           )
       );
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function openResultsAction()
    {
       
       if ($this->identity()) {
        
           return new ViewModel(
               array('pairings' => $this->getAllOpenResults())
           );
       } 
    
       else {
           return $this->redirect()->toRoute('login');
       }
    
       
    }
    
    
    protected function getTopTable()
    {
       
        
       $repository = $this->getEntityManager()->getRepository(
           'League\Entity\Position'
       );
       
       $position = $repository->findBy(
           array('_lid' => 1), 
           array(
              '_win'=> 'DESC', 
              '_tiebreakerA'=> 'DESC',
              '_tiebreakerB'=> 'DESC',
              '_gamesPlayed'=> 'DESC',
              '_id'=> 'DESC'
              )
       );
       
       return $position;
       
    }
    
    protected function getNextThreeGames()
    {
       
       
       $query="SELECT u FROM League\Entity\Pairing u 
           WHERE u._lid=1 AND u._resultId IS NULL 
           AND u._date >= :today ORDER BY u._date ASC";
       
       $pairings = $this->getEntityManager()->createQuery($query);
       $pairings->setParameter('today', new \DateTime());
       $pairings->setMaxResults(3);
       
       
       return $pairings->getResult();
       
    }
    
    protected function getAllOpenResults()
    {
       
       
       $query="SELECT u FROM League\Entity\Pairing u 
           WHERE u._lid=1 AND u._resultId IS NULL 
           AND u._date < :today ORDER BY u._date ASC";
       
       $pairings = $this->getEntityManager()->createQuery($query);
       $pairings->setParameter('today', new \DateTime());
              
       return $pairings->getResult();
       
    }
    
    
    protected function getNextGame()
    {
       
        
       $repository = $this->getEntityManager()->getRepository(
           'League\Entity\Pairing'
       );
       
       
       //@todo: datumsvergleich jetzt zu nächsten termin
       //@todo: nur aktuelle Termine, nicht die Spiele,
       //die noch nicht eingetragen sind 
       
       $position = $repository->findBy(
           array('_lid' => 1, '_resultId' => NULL,), 
           array(
              '_date'=> 'ASC', 
              ),
           1    
       );
       
       return $position;
       
    }
    
    protected function getResultlist()
    {
       
        
       $repository = $this->getEntityManager()->getRepository(
           'League\Entity\Result'
       );
       
       
       //@todo: datumsvergleich jetzt zu nächsten termin
       //@todo: nur aktuelle Termine, nicht die Spiele,
       //die noch nicht eingetragen sind 
       
       $game = $repository->findAll();
       
       return $game;
       
    }
    
    public function addAction()
    {
        
        $pid = (int) $this->params()->fromRoute('id', 0);
        $game= (object) $this->getEntityManager()->find('League\Entity\Pairing',
                $pid);
        
        $repository = $this->getEntityManager()->getRepository(
           'League\Entity\Position');
        
        $black= $repository->findOneBy(
                array('_uid' => $game->getBlackId())
                );
        $white= $repository->findOneBy(
                array('_uid' => $game->getWhiteId())
                );
        
        $form = new ResultForm($game, $this->getResultlist());
        $form->setBindOnValidate(false);
        $form->bind($game);
        
         
        $request = $this->getRequest();
               
        if ($request->isPost()) {
            
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                
                $form->bindValues();
                $this->getEntityManager()->flush();
                
                $calc = new PositionCalculator($request->getPost());     
                $calc->bindEntity($black);
                $calc->bindEntity($white);
               
                $this->getEntityManager()->flush($black);
                $this->getEntityManager()->flush($white);
                
                // Redirect to list of albums
                return $this->redirect()->toRoute('league');
            }
        }
        
          
        return array('id' => $pid, 'game' => $game, 'form' => $form);
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
    
    
}
