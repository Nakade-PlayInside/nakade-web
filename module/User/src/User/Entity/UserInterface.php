<?php
namespace User\Entity;

interface UserInterface
{

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