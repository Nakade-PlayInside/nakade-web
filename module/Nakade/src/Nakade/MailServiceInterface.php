<?php
namespace Nakade;

/**
 * Interface FormServiceInterface
 *
 * @deprecated remove after refactoring -> abstractMailService
 *
 * @package Nakade
 */
interface MailServiceInterface
{
//todo: remove after refactoring -> abstractMailService
    /**
     * @param string $typ
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function getMail($typ);


}