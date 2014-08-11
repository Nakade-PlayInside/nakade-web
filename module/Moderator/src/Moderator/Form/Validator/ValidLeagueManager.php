<?php
namespace Moderator\Form\Validator;

use Moderator\Form\ManagerInterface;
use Zend\Validator\Exception;
use Zend\Stdlib\ArrayUtils;
use Doctrine\ORM\EntityManager;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception\InvalidArgumentException;

/**
 * Class ValidManager
 *
 * @package Moderator\Form\Validator
 */
class ValidLeagueManager extends AbstractValidator implements ManagerInterface
{
    const ERROR_NO_RECORD_FOUND = 'noRecordFound';
    const ERROR_USED  = 'used';
    const ERROR_EXPIRED  = 'expired';

    private $associationId;
    private $entity;
    private $entityManager;

    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => "Coupon code invalid.",
        self::ERROR_USED    => "Coupon code already used.",
        self::ERROR_EXPIRED    => "Coupon code is expired.",
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

    /*    if (!array_key_exists('adapter', $options)) {
            throw new InvalidArgumentException('No entity manager provided');
        }
        $this->entityManager = $options['adapter'];*/
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value, $context=null)
    {//todo
        $this->setValue($value);

        if (isset($context[self::ASSOCIATION])) {
            $this->associationId = $context[self::ASSOCIATION];
        }

        if ($this->isNotInDB()) {
            $this->error(self::ERROR_NO_RECORD_FOUND);
            return false;
        }
        if ($this->getEntity()->isUsed()) {
            $this->error(self::ERROR_USED);
            return false;
        }
        if ($this->getEntity()->isExpired()) {
            $this->error(self::ERROR_EXPIRED);
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    private function isNotInDB()
    {
        return is_null($this->getEntity());
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return \User\Entity\Coupon
     */
    private function getEntity()
    {
        if (is_null($this->entity)) {

            $em = $this->getEntityManager();
            $qb = $em->createQueryBuilder('Coupon')
                ->select('c')
                ->from('Moderator\Entity\LeagueManager', 'm')
                ->where('m.association = :code')
                ->setParameter('code', $this->getValue());

            $this->entity = $qb->getQuery()->getOneOrNullResult();
        }

        return $this->entity;
    }

}
