<?php
namespace Support\Form\Validator;

use Support\Form\ManagerInterface;
use Zend\Validator\Exception;
use Zend\Stdlib\ArrayUtils;
use Doctrine\ORM\EntityManager;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception\InvalidArgumentException;

/**
 * Class ValidManager
 *
 * @package Support\Form\Validator
 */
class ValidLeagueManager extends AbstractValidator implements ManagerInterface
{
    const ERROR_NO_ASSOCIATION = 'noAssociationFound';
    const ERROR_REGISTERED_LM  = 'registeredLM';

    private $associationId;
    private $entityManager;

    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_NO_ASSOCIATION => "Association not found.",
        self::ERROR_REGISTERED_LM    => "User already LM in this Association.",
    );

    /**
     * @param null $options
     *
     * @throws InvalidArgumentException
     */
    public function __construct($options = null)
    {
        //@todo: translate messages
        parent::__construct($options);

        if (!array_key_exists('adapter', $options)) {
            throw new InvalidArgumentException('No entity manager provided');
        }
        $this->entityManager = $options['adapter'];
    }

    /**
     * @param mixed $value
     * @param null $context
     *
     * @return bool
     */
    public function isValid($value, $context=null)
    {
        $this->setValue($value);

        if (isset($context[self::ASSOCIATION])) {
            $this->associationId = $context[self::ASSOCIATION];
        } else {
            $this->error(self::ERROR_NO_ASSOCIATION);
            return false;
        }

        if ($this->isAlreadyLeagueManager()) {
            $this->error(self::ERROR_REGISTERED_LM);
            return false;
        }

        return true;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return bool
     */
    private function isAlreadyLeagueManager()
    {
            $result = $this->getEntityManager()->createQueryBuilder('LeagueManager')
                ->select('m')
                ->from('Support\Entity\LeagueManager', 'm')
                ->innerJoin('m.association', 'Association')
                ->innerJoin('m.manager', 'Manager')
                ->where('Association.id = :associationId')
                ->andWhere('Manager.id = :managerId')
                ->setParameter('managerId', $this->getValue())
                ->setParameter('associationId', $this->getAssociationId())
                ->getQuery()
                ->getResult();

        return !empty($result);

    }

    /**
     * @return int
     */
    private function getAssociationId()
    {
        return $this->associationId;
    }



}
