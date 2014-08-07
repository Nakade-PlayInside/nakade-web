<?php
namespace League\Command;

use League\Entity\Result;
use League\Services\RepositoryService;
use League\Services\MailService;
use League\Standings\ResultInterface;
use Zend\Console\Request as ConsoleRequest;

/**
 * console command for cron job
 * crontab for www-data: php /var/www/nakade/public/index.php autoResult > /dev/null
 *
 * @package League\Command
 */
class AutoResultController extends AbstractCommandController implements ResultInterface
{
    private $resultType;

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
        $mapper =  $this->getMapper(RepositoryService::RESULT_MAPPER);
        $result = $mapper->getActualOpenResults($this->getAutoResultTime());

        /* @var $mail \League\Mail\AutoResultMail */
        $mail = $this->getMail(MailService::AUTO_RESULT_MAIL);

        echo "Found " . count($result) . " overdue matches with no result." .PHP_EOL;
        echo "Set automatically result to suspend and send " . 2*count($result) . " result mails." .PHP_EOL;

        /* @var $match \Season\Entity\Match */
        foreach ($result as $match) {

            $match->setResult($this->getResult());
            $this->getEntityManager()->persist($match);

            $mail->setMatch($match);
            $mail->sendMail($match->getBlack());
            $mail->sendMail($match->getWhite());
        }
        $this->getEntityManager()->flush();

        echo "done." . PHP_EOL;
    }

    /**
     * @return \League\Entity\Result
     */
    private function getResult()
    {
       $result = new Result();
       $result->setResultType($this->getResultType());
       $result->setDate(new \DateTime());

       $this->getEntityManager()->persist($result);
       return $result;
    }


    /**
     * @return \League\Entity\ResultType
     */
    private function getResultType()
    {
        if (is_null($this->resultType)) {
            $this->resultType = $this->getEntityManager()->getReference('League\Entity\ResultType', self::SUSPENDED);
        }
        return $this->resultType;
    }

    /**
     * @return int
     */
    private function getAutoResultTime()
    {
        $time = 72; //default 72h
        $config  = $this->getServiceLocator()->get('config');
        if (isset($config['League']['auto_result_time'])) {
            $time =  intval($config['League']['auto_result_time']);
        }
        return $time;
    }

}