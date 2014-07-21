<?php
namespace User\Form\Validator;

use Zend\Validator\Exception;
use Zend\Stdlib\ArrayUtils;
use Doctrine\ORM\EntityManager;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception\InvalidArgumentException;

/**
 * Class DBNoRecordExist
 *
 * @package User\Form\Validator
 */
class DBNoRecordExist extends AbstractValidator
{

    /**
     * Error constants
     */
    const ERROR_NO_RECORD_FOUND = 'noRecordFound';
    const ERROR_RECORD_FOUND    = 'recordFound';

    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => "No record matching the input was found.",
        self::ERROR_RECORD_FOUND    => "A record matching the input was found.",
    );

    private $entity = '';
    private $property = '';
    private $entityManager = null;
    private $excludeId  = -1;

    /**
     * @param null $options
     *
     * @throws InvalidArgumentException
     */
    public function __construct($options = null)
    {
        //@todo: translate messages
        parent::__construct($options);

        if ($options instanceof \Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new InvalidArgumentException(
                'The options parameter must be an array or a Traversable'
            );
        }

        if (!array_key_exists('entity', $options)) {
            throw new InvalidArgumentException('Entity option missing!');
        }
        $this->entity =$options['entity'];

        if (!array_key_exists('property', $options)) {
            throw new InvalidArgumentException('Property option (field to search for) missing!');
        }
        $this->property = $options['property'];

        if (!array_key_exists('entityManager', $options)) {
            throw new InvalidArgumentException('No entity manager provided');
        }
        $this->entityManager = $options['entityManager'];

        if (array_key_exists('excludeId', $options)) {
            $this->excludeId = $options['excludeId'];
        }
    }

    /**
     * @param mixed $value
     *
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function isValid($value)
    {
        $this->setValue($value);
        $result = $this->isExisting($value);
        if ($result) {
            $this->error(self::ERROR_RECORD_FOUND);
        }

        return !$result;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    private function isExisting($value)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('query');
        $qb->select('object')
            ->from($this->getEntity(), 'object')
            ->where('object.' . $this->property . ' = :value')
            ->andWhere('object.' . $this->getIdentifier() . ' != :exclude')
            ->setParameter('value', $value)
            ->setParameter('exclude', $this->excludeId);

        $result = $qb->getQuery()->getResult();
        return !empty($result);
    }

    /**
     * @return string
     */
    private function getIdentifier()
    {
        return $this->getEntityManager()
            ->getClassMetaData($this->getEntity())
            ->getSingleIdentifierFieldName();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

}
