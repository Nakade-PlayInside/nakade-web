<?php
namespace Nakade\Abstracts;

use Zend\Form\Form;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Doctrine\ORM\EntityManager;

/**
 * Abstract form implementing a translator.
 * Using the translate method will return the given string if
 * no translator is set.
 * Using bindingEntity for setting values will reset the filter.
 */
abstract class AbstractForm extends Form implements TranslatorAwareInterface
{

    protected $translator;
    protected $textDomain="default";
    protected $entityManager=null;
    protected $id;
    protected $enabled=true;


   /**
   * Sets the EntityManager
   *
   * @param EntityManager $em
   *
   * @return $this;
   */
   public function setEntityManager(EntityManager $em)
   {
      $this->entityManager = $em;
      return $this;
   }

  /**
   * @return \Doctrine\ORM\EntityManager
   */
   public function getEntityManager()
   {
      return $this->entityManager;
   }

   /**
   * @return bool
   */
   public function hasEntityManager()
   {
      return isset($this->entityManager);
   }

   /**
    * Returns the primary key of the given
    * entity or null if the entity manager is not set.
    *
    * @param object $object
    *
    * @return null|string
    */
   public function getIdentifierKey($object)
   {
       if ($this->hasEntityManager()) {
           return $this->getEntityManager()
                 ->getClassMetaData(get_class($object))
                 ->getSingleIdentifierFieldName();
       }
       return null;

   }

   /**
    * get the id value
    *
    * @return int
    */
    public function getIdentifierValue()
    {
        return $this->id;
    }

    /**
     * set the id value
     *
     * @param int $value
     *
     * @return $this;
     */
    public function setIdentifierValue($value)
    {
        $this->id=$value;
        return $this;
    }

    /**
     * Binds an entitiy object to the form, populating
     * the form values. The identifier of the
     * entity is set and the filter are reset. This is mandatory
     * if the DBNoRecordExist validator is used with the exclude
     * option.
     *
     * @param object $object
     */
    public function bindEntity($object)
    {
        if (is_null($object)) {
            return;
        }

        $this->bind($object);

        //removes leading underlines
        $temp = $this->getIdentifierKey($object);
        $identifier = str_replace('_', '', $temp);

        $method = 'get'.ucfirst($identifier);
        $method = 'getId';

        if (method_exists($object, $method)) {
              $value = $object->$method();
              $this->setIdentifierValue($value);

        }

        $filter = $this->getFilter();
        $this->setInputFilter($filter);
    }

    /**
     * You have to implement this for setting the filter
     * and validators
     */
    abstract public function getFilter();


    /**
    * saves the entity.
    * return true on success
    *
    * @param object $entity
    */
   public function save($entity)
   {

       if (is_null($entity)) {
           $this->getEntityManager()->flush();
           return;
       }

       $this->getEntityManager()->persist($entity);
       $this->getEntityManager()->flush($entity);

   }


    /**
     * @param Translator $translator
     *
     * @param string     $textDomain
     *
     * @return Translator|TranslatorAwareInterface
     */
    public function setTranslator(Translator $translator = null, $textDomain = null)
    {
        if (isset($translator)) {
            $this->translator=$translator;
        }

        if (isset($textDomain)) {
            $this->textDomain=$textDomain;
        }
        return $translator;
    }

    /**
     * Returns translator used in object
     *
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Checks if the object has a translator
     *
     * @return bool
     */
    public function hasTranslator()
    {
        return isset($this->translator);
    }

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function setTranslatorEnabled($enabled = true)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTranslatorEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set translation text domain
     *
     * @param string $textDomain
     *
     * @return $this
     */
    public function setTranslatorTextDomain($textDomain = 'default')
    {
        $this->textDomain=$textDomain;
        return $this;
    }

    /**
     * Return the translation text domain
     *
     * @return string
     */
    public function getTranslatorTextDomain()
    {
        return $this->textDomain;
    }

    /**
    * Helper for i18N. If a translator is set to the controller, the
    * message is translated.
    *
    * @param string $message
     *
    * @return string
    */
   public function translate($message)
   {

       $translator = $this->getTranslator();
       if (is_null($translator)) {
           return $message;
       }

       return $translator->translate(
           $message,
           $this->getTranslatorTextDomain()
       );

   }

}

