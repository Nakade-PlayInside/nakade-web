<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;

/**
 * Abstract form implementing a translator.
 * Using the translate method will return the given string if
 * no translator is set.
 */
abstract class AbstractForm 
            extends Form 
            implements TranslatorAwareInterface
{
   
    protected $_translator;
    protected $_textDomain="default";
    protected $_entity_manager=null;
    protected $_id;
    
    
   /**
   * Sets the EntityManager
   *
   * @param EntityManager $entitymanager
   * @access public
   * @return ActionController
   */
   public function setEntityManager($em)
   {
      $this->_entity_manager = $em;
      return $this;
   }

  /**
   * Returns the EntityManager
   *
   * Fetches the EntityManager from ServiceLocator if it has not been initiated
   * and then returns it
   *
   * @access public
   * @return EntityManager
   */
   public function getEntityManager()
   {
      return $this->_entity_manager;
   }
   
   /**
   * Returns true if EntityManager is set
   *
   * @access public
   * @return bool
   */
   public function hasEntityManager()
   {
      return isset($this->_entity_manager);
   }
   
   /**
    * Returns the primary key of the given
    * entity or null if the entity manager is not set.
    * 
    * @param entity $object
    * @return null|string
    */
   public function getIdentifierKey($object)
   {
       if($this->hasEntityManager()) {
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
        return $this->_id;
    }  
    
    /**
     * set the id value
     * 
     * @param int $value
     * @return \User\Form\AbstractForm
     */
    public function setIdentifierValue($value)
    {
        $this->_id=$value;
        return $this;
    }       
   
    /**
     * Binds an entitiy object to the form, populating
     * the form values. The identifier of the 
     * entity is set and the filter are reset. This is mandatory
     * if the DBNoRecordExist validator is used with the exclude 
     * option.
     * 
     * @param Entity $object
     */
    public function bindEntity($object)
    {
        $this->bind($object);
        $identifier = $this->getIdentifierKey($object);
            
        $method = 'get'.ucfirst($identifier);
        if(method_exists($object, $method)) {
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
    * @param Entity $entity
    */
   public function save($entity)
   {
       
       if($entity===null) {
           return $this->getEntityManager()->flush();
       }
    
       $this->getEntityManager()->persist($entity);
       $this->getEntityManager()->flush($entity);
       
   }
   
    
    /**
     * Sets translator to use in helper
     *
     * @param  Translator $translator  [optional] translator.
     *          Default is null, which sets no translator.
     * @param  string     $textDomain  [optional] text domain
     *          Default is null, which skips setTranslatorTextDomain
     * @return TranslatorAwareInterface
     */
    public function setTranslator(
            Translator $translator = null, 
            $textDomain = null
            ) 
    {
        if(isset($translator))
            $this->_translator=$translator;
    
        if(isset($textDomain))
            $this->_textDomain=$textDomain;
    }

    /**
     * Returns translator used in object
     *
     * @return Translator|null
     */
    public function getTranslator()
    {   
        return $this->_translator;
    }

    /**
     * Checks if the object has a translator
     *
     * @return bool
     */
    public function hasTranslator()
    {
        return isset($this->_translator);
    }

    /**
     * Sets whether translator is enabled and should be used
     *
     * @param  bool $enabled [optional] whether translator should be used.
     *                       Default is true.
     * @return TranslatorAwareInterface
     */
    public function setTranslatorEnabled($enabled = true) {;}
    
    /**
     * Returns whether translator is enabled and should be used
     *
     * @return bool
     */
    public function isTranslatorEnabled() {;}

    /**
     * Set translation text domain
     *
     * @param  string $textDomain
     * @return TranslatorAwareInterface
     */
    public function setTranslatorTextDomain($textDomain = 'default')
    {
        $this->_textDomain=$textDomain;
    }

    /**
     * Return the translation text domain
     *
     * @return string
     */
    public function getTranslatorTextDomain()
    {
        return $this->_textDomain;
    }
    
    /**
    * Helper for i18N. If a translator is set to the controller, the 
    * message is translated.
    *  
    * @param string $message
    * @return string
    */
   public function translate($message) 
   {
   
       $translator = $this->getTranslator();
       if ($translator===null)
           return $message;
       
       return $translator->translate(
                  $message, 
                  $this->getTranslatorTextDomain()
              );
       
   }
    
}
?>
