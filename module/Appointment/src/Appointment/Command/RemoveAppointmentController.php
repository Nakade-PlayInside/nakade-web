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
 * crontab for www-data: php /var/www/nakade/public/index.php removeAppointment > /dev/null
 *
 * @package Appointment\Command
 */
class RemoveAppointmentController extends AbstractActionController
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

       /* @var $repo \Appointment\Mapper\AppointmentMapper */
       $repo = $repoService->getMapper(RepositoryService::APPOINTMENT_MAPPER);
       $result = $repo->getExpiredAppointments();

       echo "Found " . count($result) . " expired appointment(s)" .PHP_EOL;

       /* @var $appointment \Appointment\Entity\Appointment */
       foreach ($result as $appointment) {

           $repo->delete($appointment);
           echo "Removed appointment (id=" . $appointment->getId() . ")" . PHP_EOL;
       }

       echo "done." . PHP_EOL;

   }

}