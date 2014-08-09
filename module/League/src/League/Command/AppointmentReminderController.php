<?php
/**
 * Controller Appointment
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

namespace League\Command;

use League\Entity\AppointmentReminder;
use League\Entity\MatchReminder;
use League\Services\RepositoryService;
use League\Services\MailService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

/**
 * console command for cron job
 * crontab for www-data: php /var/www/nakade/public/index.php appointmentReminder > /dev/null
 *
 * @package League\Command
 */
class AppointmentReminderController extends AbstractCommandController
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


       /* @var $mail \League\Mail\AppointmentReminderMail */
       $mail = $this->getMail(MailService::APPOINTMENT_REMINDER_MAIL);

       /* @var $mapper \League\Mapper\ScheduleMapper */
       $mapper = $this->getMapper(RepositoryService::SCHEDULE_MAPPER);
       $result = $mapper->getMatchesForAppointmentReminder($this->getDays());

       echo "Found " . count($result) . " matches being played in the next ". $this->getDays() ." days." .PHP_EOL;
       echo "Sending " . 2*count($result) . " appointment reminder mails." .PHP_EOL;

       /* @var $match \Season\Entity\Match */
       foreach ($result as $match) {
           $mail->setMatch($match);
           $mail->sendMail($match->getBlack());
           $mail->sendMail($match->getWhite());

           $reminder = new AppointmentReminder();
           $reminder->setMatch($match);
           $this->getEntityManager()->persist($reminder);
       }
       $this->getEntityManager()->flush();

       echo "done." . PHP_EOL;

   }

    /**
     * @return int
     */
    private function getDays()
    {
        $days = 5;
        $config  = $this->getServiceLocator()->get('config');
        if (isset($config['League']['appointment_reminder'])) {
            $days =  intval($config['League']['appointment_reminder']);
        }
        return $days;
    }

}