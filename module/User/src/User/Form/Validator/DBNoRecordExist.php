<?php
namespace User\Form\Validator;

use Nakade\Abstracts\AbstractDoctrineValidator;
/**
 * Validating a value against an existing database record using
 * doctrine. Doctrine has to be installed and configured.
 * Use a factory for providing the entity manager.
 *
 * The following additional option keys are supported:
 * 'entity'    => 'doctrine entity',
 * 'property'  => 'field or better property of the entity to look for',
 * 'adapter'   => 'entity manager'
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class DBNoRecordExist extends AbstractDoctrineValidator
{

    /**
     * @param mixed $value
     *
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function isValid($value)
    {
        /*
         * Check for an adapter being defined. If not, throw an exception.
         */
        if (null === $this->adapter) {
            throw new \RuntimeException('No database adapter present');
        }

        $valid = true;
        $this->setValue($value);

        $result = $this->query($value);
        if ($result) {
            $valid = false;
            $this->error(self::ERROR_RECORD_FOUND);
        }

        return $valid;
    }


}
