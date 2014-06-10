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
 * crontab for www-data: php /var/www/nakade/public/index.php autoResult > /dev/null
 *
 * @package League\Command
 */
class AutoResultController extends AbstractActionController
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
       $time = 72;
       $config  = $sm->get('config');
       if (isset($config['League']['auto_result_time'])) {
           $time =  strval($config['League']['auto_result_time']);
       }

       /* @var $mail \League\Mail\AutoResultMail */
       $mail = $mailService->getMail(MailService::AUTO_RESULT_MAIL);

       /* @var $repo \League\Mapper\ScheduleMapper */
       $repo = $repoService->getMapper(RepositoryService::SCHEDULE_MAPPER);
       //todo: abfrage open matches mit datum ggf auch mit user config
       $result = $repo->getOverdueAppointments($time);

       echo "Found " . count($result) . " open matches" .PHP_EOL;

       /* @var $match \Season\Entity\Match */
       foreach ($result as $match) {

           //todo: saving match with result
           $mail->setMatch($match);
           $mail->sendMail($match->getBlack());
           $mail->sendMail($match->getWhite());
           echo "Send auto result info (id=" . $match->getId() . ")" . PHP_EOL;
       }

       echo "done." . PHP_EOL;

   }

}