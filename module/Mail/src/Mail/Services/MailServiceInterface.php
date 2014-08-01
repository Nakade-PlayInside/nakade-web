<?php
namespace Mail\Services;

/**
 * Interface MailServiceInterface
 *
 * @package Mail\Services
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