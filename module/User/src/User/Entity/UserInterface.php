<?php
namespace User\Entity;

interface UserInterface
{

    const CERTIFICATE_NAME_LENGTH = 25;

    /**
     * Returns the email
     *
     * @return string
     */
    public function getEmail();

    /**
     * @return string
     */
    public function getLanguage();

    /**
     * get name
     *
     * @return string
     */
    public function getName();

}