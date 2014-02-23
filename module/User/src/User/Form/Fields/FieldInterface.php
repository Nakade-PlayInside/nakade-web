<?php
namespace User\Form\Fields;

/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 23.02.14
 * Time: 14:44
 */

interface FieldInterface
{
    /**
     * @return array
     */
    public function getField();

    /**
     * @return array
     */
    public function getFilter();
}