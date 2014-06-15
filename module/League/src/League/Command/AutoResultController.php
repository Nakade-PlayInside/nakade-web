<?php
/**
 * Controller Appointment
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */

namespace League\Command;

use League\Services\RepositoryService;
use League\Services\MailService;
use League\Standings\ResultInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

/**
 * console command for cron job
 * crontab for www-data: php /var/www/nakade/public/index.php autoResult > /dev/null
 *
 * @package League\Command
 */
class AutoResultController extends AbstractActionController implements ResultInterface
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

       /* @var $repo \League\Mapper\ResultMapper */
       $repo = $repoService->getMapper(RepositoryService::RESULT_MAPPER);
       $result = $repo->getActualOpenResults($time);
       $suspend = $repo->getEntityManager()->getReference('League\Entity\Result', self::SUSPENDED);

       echo "Found " . count($result) . " overdue matches" .PHP_EOL;

       /* @var $match \Season\Entity\Match */
       foreach ($result as $match) {

           $match->setResult($suspend);
           $repo->save($match);

           $mail->setMatch($match);
           $mail->sendMail($match->getBlack());
           $mail->sendMail($match->getWhite());
           echo "Send auto result info (id=" . $match->getId() . ")" . PHP_EOL;
       }

       echo "done." . PHP_EOL;

   }

}