<?php
/**
 * Controller Appointment
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

namespace Appointment\Command;

use Appointment\Services\RepositoryService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

/**
 * console command for cron job
 * crontab for www-data: php /var/www/nakade/public/index.php autoConfirm > /dev/null
 *
 * @package Appointment\Command
 */
class CommandController extends AbstractActionController
{

    /**
     * @throws \RuntimeException
     */
   public function doAction()
   {
       $request = $this->getRequest();

       // Make sure that we are running in a console and the user has not tricked our
       // application into running this action from a public web server.
       if (!$request instanceof ConsoleRequest) {
           throw new \RuntimeException('You can only use this action from a console!');
       }

       $sm = $this->getServiceLocator();
       $repoService = $sm->get('Appointment\Services\RepositoryService');
       $mailService = $sm->get('Appointment\Services\MailService');

       //time
       $time = 48;
       $config  = $sm->get('config');
       if (isset($config['Appointment']['auto_confirm_time'])) {
           $time =  strval($config['Appointment']['auto_confirm_time']);
       }

       /* @var $mail \Appointment\Mail\ConfirmMail */
       $mail = $mailService->getMail('confirm');

       /* @var $repo \Appointment\Mapper\AppointmentMapper */
       $repo = $repoService->getMapper(RepositoryService::APPOINTMENT_MAPPER);
       $result = $repo->getOverdueAppointments($time);

       echo "Found " . count($result) . " open appointment(s)" .PHP_EOL;

       /* @var $appointment \Appointment\Entity\Appointment */
       foreach ($result as $appointment) {

           $newDate = $appointment->getNewDate();
           $match = $appointment->getMatch();
           $match->setDate($newDate);
           $appointment->setIsConfirmed(true);
           $sequence = $match->getSequence() + 1;
           $match->setSequence($sequence);

           $repo->save($match);
           $repo->save($appointment);

           $mail->setAppointment($appointment);
           $mail->sendMail($appointment->getResponder());
           $mail->sendMail($appointment->getSubmitter());
           echo "Confirmed appointment (id=" . $appointment->getId() . ")" . PHP_EOL;
       }

       echo "done." . PHP_EOL;

   }

}