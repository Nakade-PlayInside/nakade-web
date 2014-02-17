<?php
namespace Nakade\Abstracts;

use Traversable;
use Zend\Stdlib\ArrayUtils;
use Zend\Validator\Exception;
use Nakade\Abstracts\AbstractNakadeValidator;

/**
 * Class for Database record validation.
 * Based on the Zend AbstractDb, this class handles Doctrine
 * db access for validating.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
abstract class AbstractDoctrineValidator extends AbstractNakadeValidator
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

    /**
     * @var string
     */
    protected $entity = '';

    /**
     * @var string
     */
    protected $property = '';

    /**
     * Entity manager to use. If null isValid() will throw an exception
     *
     * @var Doctrine\ORM\Mapping\Driver\AnnotationDriver
     */
    protected $adapter = null;
    
    /**
     *  @var int
     */
    protected $exclude = null;

     public function getTranslatedTemplate()
    {
        //just for translation using PoE
        $template = array(
            self::ERROR_NO_RECORD_FOUND => $this->translate(
                "No record matching the input was found."),
            self::ERROR_RECORD_FOUND    => $this->translate(
                "A record matching the input was found."),
           
        );
        return $template;
    }

    
    /**
     * Provides basic configuration for use with Zend\Validator\Db Validators
     * A database adapter may optionally be supplied to avoid using the registered default adapter.
     *
     * The following option keys are supported:
     * 'entity'   => The doctrine entity to validate against
     * 'property' => The property to check for a match
     * 'exclude'  => Entity to exclude. Provide the primary key value
     * 'adapter'  => An optional doctrine adapter to use
     *
     * @param array|Traversable|Select $options Options to use for this validator
     * @throws \Zend\Validator\Exception\InvalidArgumentException
     */
    public function __construct($options = null)
    {
       
        parent::__construct($options);

        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new Exception\InvalidArgumentException(
                'The options parameter must be an array or a Traversable'
            );
        }
        
        if (!array_key_exists('entity', $options)) {
            throw new Exception\InvalidArgumentException('Entity option missing!');
        }
        $this->setEntity($options['entity']);
        
        if (!array_key_exists('property', $options)) {
            throw new Exception\InvalidArgumentException('Property option missing!');
        }
        $this->setProperty($options['property']);
        
        if (array_key_exists('adapter', $options)) {
            $this->setAdapter($options['adapter']);
        }
        
        if (array_key_exists('exclude', $options)) {
            $this->setExclude($options['exclude']);
        }
        
    }

    /**
     * Returns the set adapter
     *
     * @throws \Zend\Validator\Exception\RuntimeException When no database adapter is defined
     * @return Doctrine Entity Manager
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Sets a new entity manager
     *
     * @param  entity manager $adapter
     * @return self Provides a fluent interface
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Returns the set property
     *
     * @return string|array
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Sets a new property
     *
     * @param string $property
     * @return AbstractDb
     */
    public function setProperty($property)
    {
        $this->property = (string) $property;
        return $this;
    }

    /**
     * Returns the set Entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Sets a new Entity
     *
     * @param string $entity
     * @return self Provides a fluent interface
     */
    public function setEntity($entity)
    {
        $this->entity = (string) $entity;
        return $this;
    }
    
    /**
     * Returns the set excluded Id
     *
     * @return string
     */
    public function getExclude()
    {
        return $this->exclude;
    }

    /**
     * Sets a new excluded Id
     *
     * @param int $value
     * @return self Provides a fluent interface
     */
    public function setExclude($value)
    {
        if(is_int($value)) {
            $this->exclude = $value;
        }
        return $this;
    }
    
    /**
     * is an exlusion set
     *
     * @return bool
     */
    public function hasExclude()
    {
        return (bool) isset($this->exclude);
    }

    /**
     * Returns the identifier of the provided entity.
     * Does not work on composite primary keys
     * @return string
     */
    protected function getIdentifier()
    {
        return $this->getAdapter()
                    ->getClassMetaData($this->getEntity())
                    ->getSingleIdentifierFieldName();
    }

    /**
     * return the exclusion query. Has to be concated 
     * to the regular query
     * 
     * @return string
     */
    protected function getExludingQuery() 
    {
        return sprintf(" AND q.%s!=%s", 
            $this->getIdentifier(),
            $this->getExclude()    
        );
        
    }
    
    /**
     * Run query and returns matches, or null if no matches are found.
     *
     * @param  string $value
     * @return array when matches are found.
     */
    protected function query($value)
    {
     
         $dql = sprintf("SELECT q as query FROM %s q WHERE q.%s=:value", 
                    $this->getEntity(), 
                    $this->getProperty()
                );
      
         if($this->hasExclude()) {
            $dql .= $this->getExludingQuery();
         }
         
         $result = $this->getAdapter()
                        ->createQuery($dql)
                        ->setParameter('value', $value)
                        ->getOneOrNullResult();
      
         return $result['query'];
      
    }

}

?>
