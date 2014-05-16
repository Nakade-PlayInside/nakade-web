<?php
namespace User\Entity;

interface UserInterface
{

    /**
     * Sets the email
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email);

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