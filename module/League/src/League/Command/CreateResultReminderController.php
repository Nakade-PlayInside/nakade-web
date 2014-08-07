<?php
namespace League\Command;

use League\Entity\ResultReminder;
use League\Services\RepositoryService;
use Zend\Console\Request as ConsoleRequest;

/**
 * console command for cron job to make new match reminder
 * crontab for www-data: php /var/www/nakade/public/index.php createResultReminder > /dev/null
 *
 * @package League\Command
 */
class CreateResultReminderController extends AbstractCommandController
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
       $result = $mapper->getNewOverdueMatches();

       echo "Found " . count($result) . " new overdue matches with no result." .PHP_EOL;
       echo "Making " . count($result) . " new result reminder." .PHP_EOL;

       /* @var $match \Season\Entity\Match */
       foreach ($result as $match) {

           $date = $match->getDate();
           $reminderDate = clone $date;
           $reminderDate->modify('+ 8 hour');

           $reminder = new ResultReminder();
           $reminder->setMatch($match);
           $reminder->setNextDate($reminderDate);
           $this->getEntityManager()->persist($reminder);
       }

       $this->getEntityManager()->flush();

       echo "done." . PHP_EOL;

   }


}