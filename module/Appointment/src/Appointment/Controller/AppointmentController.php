<?php
/**
 * Controller Appointment
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

// module/Appointment/src/Appointment/Controller/AppointmentController.php:
namespace Appointment\Controller;

use Appointment\Form\AppointmentForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AppointmentController extends AbstractActionController
{

    /**
     * @return array|ViewModel
     */
   public function indexAction()
   {
       $user = $this->identity();
       #match id=6

       $sm = $this->getServiceLocator();
       $em = $sm->get('Doctrine\ORM\EntityManager');

       $repo = new \League\Mapper\MatchMapper();
       $repo->setEntityManager($em);

       /* @var $match \League\Entity\Match */
       $match = $repo->getMatchById(6);
       $black = $match->getBlack();
       $white = $match->getWhite();

       //works for this league only
       $lid = $match->getLid();
       $blackMatches = $repo->getNextMatchDatesInLeagueByUser($black, $lid);
       $whiteMatches = $repo->getNextMatchDatesInLeagueByUser($white, $lid);

       $matchInfo = sprintf("%s: %s - %s",
           $match->getDate()->format('d.M  H:i'),
           $match->getBlack()->getShortName(),
           $match->getWhite()->getShortName()
       );

       $dates = array_merge($blackMatches, $whiteMatches);
       $form = new AppointmentForm();

       return new ViewModel(
           array(
               'appointment' => null,
               'form' => $form,
               'matchInfo' => $matchInfo
           )
       );
   }


}