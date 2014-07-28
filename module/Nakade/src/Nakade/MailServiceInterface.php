<?php
namespace Nakade;

/**
 * Interface FormServiceInterface
 *
 * @package Nakade
 */
interface MailServiceInterface
{

    /**
     * @param string $typ
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function getMail($typ);


}