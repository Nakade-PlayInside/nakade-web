<?php
namespace User\Form\Validator;

use Zend\Validator\Exception;
use Zend\Validator\AbstractValidator;

/**
 * validate against common passwords
 *
 * @package User\Form\Validator
 */
class CommonPassword extends AbstractValidator
{

    const NO_COMMON  = 'NoCommonPassword';

    private $commons;

    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::NO_COMMON => "Do not use common password: %value%.",
    );

    /**
     * constructor
     */
    public function __construct()
    {
       //@todo: Übersetzung
        $this->commons = array (
            'password',
            '123456',
            'qwert',
            'abc123',
            'letmein',
            'myspace',
            'monkey',
            'iloveyou',
            'sunshine',
            'trustno1',
            'welcome',
            'shadow',
        );
        parent::__construct(null);
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public function isValid($value)
    {

        $value = strval($value);
        $this->setValue($value);

        $result = $this->isCommonPassword($value);
        if ($result) {
            $this->error(self::NO_COMMON);
        }

        return !$result;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    private function isCommonPassword($value)
    {
        return in_array(strtolower($value), $this->commons);
    }

}
