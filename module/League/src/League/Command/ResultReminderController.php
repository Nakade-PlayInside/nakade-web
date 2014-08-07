<?php
namespace League\Command;

use League\Services\RepositoryService;
use League\Services\MailService;
use Zend\Console\Request as ConsoleRequest;

/**
 * console command for cron job
 * crontab for www-data: php /var/www/nakade/public/index.php resultReminder > /dev/null
 *
 * @package League\Command
 */
class ResultReminderController extends AbstractCommandController
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

       /* @var $mapper \League\Mapper\ResultMapper */
       $mapper = $this->getMapper(RepositoryService::RESULT_MAPPER);
       $result = $mapper->getResultReminder();
       $mail = $this->getMail(MailService::RESULT_REMINDER_MAIL);

       echo "Found " . count($result) . " open matches" .PHP_EOL;
       echo "Sending " . 2*count($result) . " reminder mails" .PHP_EOL;

       /* @var $reminder \League\Entity\ResultReminder */
       foreach ($result as $reminder) {

           $mail->setMatch($reminder->getMatch());
           $mail->sendMail($reminder->getMatch()->getBlack());
           $mail->sendMail($reminder->getMatch()->getWhite());

           $date = $reminder->getNextDate();
           $next = clone $date;
           $next->modify('+8 hour');
           $reminder->setNextDate($next);
           $this->getEntityManager()->persist($reminder);
           $this->getEntityManager()->flush();
       }

       echo "done." . PHP_EOL;

   }

}