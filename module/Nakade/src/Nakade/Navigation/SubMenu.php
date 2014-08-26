<?php
namespace Nakade\Navigation;

use \Zend\Navigation\Page\AbstractPage;

class SubMenu extends AbstractPage
{
    public function getHref()
    {
        return '#';
    }

    public function getClass()
    {
        return 'dropdown-toggle';
    }

    public function getId()
    {
        return 'drop1';
    }
}
