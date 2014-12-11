<?php
/**
 * Controller Appointment
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

namespace Stats\Command;

use Appointment\Services\MailService;
use Appointment\Services\RepositoryService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

/**
 * console command for cron job
 * crontab for www-data: php /var/www/nakade/public/index.php autoAchievement > /dev/null
 *
 * @package Stats\Command
 */
class AutoAchievementController extends AbstractActionController
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

       //get all finished tournaments
       //get all participants by tournament
       //get rankings
       //get ratings

       $sm = $this->getServiceLocator();
       $repoService = $sm->get('Appointment\Services\RepositoryService');

       //time
       $time = 48;
       $config  = $sm->get('config');
       if (isset($config['Appointment']['auto_confirm_time'])) {
           $time =  intval($config['Appointment']['auto_confirm_time']);
       }

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

           echo "Confirmed appointment (id=" . $appointment->getId() . ")" . PHP_EOL;
       }

       echo "done." . PHP_EOL;

   }

}