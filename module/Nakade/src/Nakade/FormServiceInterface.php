<?php
namespace Nakade;

/**
 * Interface FormServiceInterface
 *
 * @package Nakade
 */
interface FormServiceInterface
{

    /**
    * @param string $typ
    *
    * @return \Zend\Form\Form
    *
    * @throws \RuntimeException
    */
    public function getForm($typ);
}