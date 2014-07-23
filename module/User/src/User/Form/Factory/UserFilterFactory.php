<?php
namespace User\Form\Factory;

use Nakade\Abstracts\AbstractTranslation;
use \Doctrine\ORM\EntityManager;
use User\Entity\User;

class UserFilterFactory extends AbstractTranslation  implements UserFieldInterface
{

    private $user;
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $name
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getFilter($name)
    {
        $filter = array();
        switch ($name) {

            case self::FIELD_BIRTHDAY :
                $filter = array(
                    'name' => self::FIELD_BIRTHDAY,
                    'required' => false,
                    'validators' => array(
                        array('name'    => 'Date',
                            'options' => array (
                                'format' => 'Y-m-d',
                            )
                        ),
                    ),
                );
                break;

            case self::FIELD_PWD :
                $filter = array(
                    'name' => self::FIELD_PWD,
                    'required' => true,
                    'filters'  => $this->getStripFilter(),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array (
                                'encoding' => 'UTF-8',
                                'min' => 6,
                                'max' => 50
                            )
                        ),
                        array('name' => 'Identical',
                            'break_chain_on_failure' => true,
                            'options' => array (
                                'token' => self::FIELD_PWD_REPEAT,
                            )
                        ),
                        array('name' => 'User\Form\Validator\PasswordComplexity',
                            'break_chain_on_failure' => true,
                        ),
                        array('name' => 'User\Form\Validator\CommonPassword',
                            'break_chain_on_failure' => true,
                        ),

                    )
                );
                break;

            case self::FIELD_EMAIL :
                $filter = array(
                    'name' => self::FIELD_EMAIL,
                    'required' => true,
                    'filters' => $this->getStripFilter(),
                    'validators'=> array(
                        array(
                            'name' => 'StringLength',
                            'options' => array (
                                'encoding' => 'UTF-8',
                                'min' => 6,
                                'max' => 120
                            )
                        ),
                        array(
                            'name' => 'EmailAddress',
                            'break_chain_on_failure' => true,
                        ),
                        $this->getDbNoRecordExist('email'),
                    )
                );
                break;

            case self::FIELD_KGS :
                $filter = array(
                    'name' => self::FIELD_KGS,
                    'required' => false,
                    'allowEmpty' => true,
                    'filters' => $this->getStripFilter(),
                    'validators'=> array(
                        array(
                            'name' => 'StringLength',
                            'options' => array (
                                'encoding' => 'UTF-8',
                                'max' => 50
                            )
                        ),
                        $this->getDbNoRecordExist(self::FIELD_KGS),
                    )
                );
                break;

            case self::FIELD_NICK :
                $filter = array(
                    'name' => self::FIELD_NICK,
                    'required' => false,
                    'allowEmpty' => true,
                    'filters' => $this->getStripFilter(),
                    'validators'=> array(
                        array(
                            'name' => 'StringLength',
                            'options' => array (
                                'encoding' => 'UTF-8',
                                'max' => 20
                            )
                        ),
                        $this->getDbNoRecordExist(self::FIELD_NICK),
                    )
                );
                break;

            case self::FIELD_TITLE :
                $filter = array(
                    'name' => self::FIELD_TITLE,
                    'required' => false,
                    'allowEmpty' => true,
                    'filters' => $this->getStripFilter(),
                    'validators'=> array(
                        array(
                            'name' => 'StringLength',
                            'options' => array (
                                'encoding' => 'UTF-8',
                                'max' => 20
                            )
                        ),
                    )
                );
                break;

            case self::FIELD_FIRST_NAME :
                $filter = array(
                    'name' => self::FIELD_FIRST_NAME,
                    'required' => true,
                    'filters' => $this->getStripFilter(),
                    'validators'=> array(
                        array(
                            'name' => 'StringLength',
                            'options' => array (
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 20
                            )
                        )
                    )
                );
                break;

            case self::FIELD_LAST_NAME :
                $filter = array(
                    'name' => self::FIELD_LAST_NAME,
                    'required' => true,
                    'filters' => $this->getStripFilter(),
                    'validators'=> array(
                        array(
                            'name' => 'StringLength',
                            'options' => array (
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 30
                            )
                        )
                    )
                );
                break;

            case self::FIELD_USERNAME :
                $filter = array(
                    'name' => self::FIELD_USERNAME,
                    'required' => true,
                    'allowEmpty' => false,
                    'filters' => $this->getStripFilter(),
                    'validators'=> array(
                        array(
                            'name' => 'StringLength',
                            'options' => array (
                                'encoding' => 'UTF-8',
                                'max' => 20
                            )
                        ),
                        $this->getDbNoRecordExist(self::FIELD_USERNAME),
                    )
                );
                break;

        }

        return $filter;

    }

    /**
     * @return \User\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
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
     * @param string $property
     *
     * @return array
     *
     * @throws \RuntimeException
     */
    private function getDbNoRecordExist($property)
    {
        $user = $this->getUser();
        if (is_null($user)) {
            throw new \RuntimeException(
                sprintf('User is not set in validation service.')
            );
        }

        return array(
            'name'     => 'User\Form\Validator\DBNoRecordExist',
            'options' => array(
                'entity'   => 'User\Entity\User',
                'property' => $property,
                'exclude'  => $this->getUser()->getId(),
                'adapter'  => $this->getEntityManager(),
            )
        );
    }

    /**
     * @return array
     */
    private function getStripFilter()
    {
        return array(
            array('name' => 'StripTags'),
            array('name' => 'StringTrim'),
            array('name' => 'StripNewLines'),
        );
    }

}
