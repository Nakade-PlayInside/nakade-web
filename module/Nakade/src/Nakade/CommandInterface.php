<?php
namespace Nakade;

/**
 * Interface CommandInterface
 *
 * @package Nakade
 */
interface CommandInterface
{

    /**
     * @return mixed
     */
    public function doAction();
}