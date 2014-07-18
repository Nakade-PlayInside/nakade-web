<?php
namespace User\Form\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class LanguageHydrator
 *
 * @package User\Form\Hydrator
 */
class LanguageHydrator implements HydratorInterface
{

    /**
     * @param \User\Entity\User $object
     *
     * @return array
     */
    public function extract($object)
    {
        $language = 'no_NO';
        if (null!==$object->getLanguage()) {
            $language = $object->getLanguage();
        }

        return array(
                'language' => $language
        );
    }

    /**
     * @param array             $data
     * @param \User\Entity\User $object
     *
     * @return \User\Entity\User
     */
    public function hydrate(array $data, $object)
    {
        $lang = null;
        if ($data['language']!= 'no_NO') {
            $lang = $data['language'];
        }
        $object->setLanguage($lang);

        return $object;
    }

}
