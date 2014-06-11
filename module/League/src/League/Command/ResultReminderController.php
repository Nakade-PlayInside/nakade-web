<?php
/**
 * Controller Appointment
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

namespace League\Command;

use League\Services\RepositoryService;
use League\Services\MailService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

/**
 * console command for cron job
 * crontab for www-data: php /var/www/nakade/public/index.php resultReminder > /dev/null
 *
 * @package League\Command
 */
class ResultReminderController extends AbstractActionController
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
       $repoService = $sm->get('League\Services\RepositoryService');
       $mailService = $sm->get('League\Services\MailService');

       //time
       $time = 12;
       $config  = $sm->get('config');
       if (isset($config['League']['result_reminder_time'])) {
           $time =  strval($config['League']['result_reminder_time']);
       }

       /* @var $mail \League\Mail\MatchReminderMail */
       $mail = $mailService->getMail(MailService::RESULT_REMINDER_MAIL);

       /* @var $repo \League\Mapper\ResultMapper */
       $repo = $repoService->getMapper(RepositoryService::RESULT_MAPPER);
       $result = $repo->getActualOpenResults($time);

       echo "Found " . count($result) . " open matches" .PHP_EOL;

       /* @var $match \Season\Entity\Match */
       foreach ($result as $match) {

           $mail->setMatch($match);
           $mail->sendMail($match->getBlack());
           $mail->sendMail($match->getWhite());
           echo "Send result reminder (id=" . $match->getId() . ")" . PHP_EOL;
       }

       echo "done." . PHP_EOL;

   }

}