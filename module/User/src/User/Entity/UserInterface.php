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
     * get name
     *
     * @return string
     */
    public function getName();

}