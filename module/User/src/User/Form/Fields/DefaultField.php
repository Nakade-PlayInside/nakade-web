<?php
namespace User\Form\Fields;

use Zend\I18n\Translator\Translator;

abstract class DefaultField implements FieldInterface
{
    private $translator;
    private $txtDomain;

    /**
     * Constructor
     */
    public function __construct(Translator $translator, $txtDomain)
    {
        $this->translator=$translator;
        $this->txtDomain=$txtDomain;
    }

    protected function translate($msg)
    {
        return $this->translator->translate($msg, $this->txtDomain);
    }


}

