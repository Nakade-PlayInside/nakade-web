<?php
namespace League\Command;

use League\Entity\ResultReminder;
use League\Services\RepositoryService;
use Zend\Console\Request as ConsoleRequest;

/**
 * console command for cron job to make new match reminder
 * crontab for www-data: php /var/www/nakade/public/index.php cleanResultReminder > /dev/null
 *
 * @package League\Command
 */
class CleanResultReminderController extends AbstractCommandController
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
        $result = $mapper->getExpiredResultReminder();

        echo "Found " . count($result) . " expired result reminder." .PHP_EOL;
        echo "Removing ..." .PHP_EOL;

        /* @var $match \League\Entity\ResultReminder */
        foreach ($result as $reminder) {
            $this->getEntityManager()->remove($reminder);
        }

        $this->getEntityManager()->flush();

        echo "done." . PHP_EOL;

    }


}