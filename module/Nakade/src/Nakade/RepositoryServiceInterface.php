<?php
namespace Nakade;

/**
 * Interface RepositoryServiceInterface
 *
 * @package Nakade
 */
interface RepositoryServiceInterface
{

    /**
     * @param string $typ
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function getMapper($typ);


}