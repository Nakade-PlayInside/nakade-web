<?php
namespace League\Command;

use League\Entity\ResultReminder;
use League\Services\RepositoryService;
use Zend\Console\Request as ConsoleRequest;

/**
 * console command for cron job to make new match reminder
 * crontab for www-data: php /var/www/nakade/public/index.php cleanMatchReminder > /dev/null
 *
 * @package League\Command
 */
class CleanMatchReminderController extends AbstractCommandController
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

        /* @var $mapper \League\Mapper\ScheduleMapper */
        $mapper = $this->getMapper(RepositoryService::SCHEDULE_MAPPER);
        $result = $mapper->getExpiredMatchReminder();

        echo "Found " . count($result) . " expired match reminder." .PHP_EOL;
        echo "Removing ..." .PHP_EOL;

        /* @var $match \League\Entity\ResultReminder */
        foreach ($result as $reminder) {
            $this->getEntityManager()->remove($reminder);
        }

        $this->getEntityManager()->flush();

        echo "done." . PHP_EOL;

    }

}