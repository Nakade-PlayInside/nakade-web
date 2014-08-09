<?php
namespace League\Command;

use Nakade\CommandInterface;
use Zend\Mvc\Controller\AbstractActionController;
/**
 * Class AbstractCommandController
 * @package League\Command
 */
abstract class AbstractCommandController extends AbstractActionController implements CommandInterface
{
    protected $mailService;
    protected $repository;
    protected $entityManager;

    /**
     * @throws \RuntimeException
     */
   abstract public function doAction();

    /**
     * @param string $typ
     *
     * @return \League\Mail\LeagueMail
     */
    public function getMail($typ)
    {
        return $this->getMailService()->getMail($typ);
    }

    /**
     * @return \League\Services\MailService
     */
    public function getMailService()
    {
        if (is_null($this->mailService)) {
            $this->mailService = $this->getServiceLocator()->get('League\Services\MailService');
        }
        return $this->mailService;
    }


    /**
     * @param string $typ
     *
     * @return \Nakade\Abstracts\AbstractMapper
     */
    public function getMapper($typ)
    {
        return $this->getRepository()->getMapper($typ);
    }

    /**
     * @return \League\Services\RepositoryService
     */
    public function getRepository()
    {
        if (is_null($this->repository)) {
            $this->repository = $this->getServiceLocator()->get('League\Services\RepositoryService');
        }
        return $this->repository;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (is_null($this->entityManager)) {
            $this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->entityManager;
    }

}