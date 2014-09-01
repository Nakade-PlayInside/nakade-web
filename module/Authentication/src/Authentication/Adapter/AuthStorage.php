<?php
namespace Authentication\Adapter;

use Zend\Authentication\Storage\Session;

/**
 * Class AuthStorage
 *
 * @package Authentication\Adapter
 */
class AuthStorage extends Session
{

    /**
     * @param bool $isRemember
     */
    public function setRememberMe($isRemember=false)
    {
        if ($isRemember) {
            $this->getSessionContainer()
                ->getManager()
                ->rememberMe();
        }
    }

    /**
     * logout
     */
    public function clear()
    {

        $this->getSessionContainer()->getManager()->forgetMe();
        parent::clear();
    }

    /**
     * @return \Zend\Session\AbstractContainer
     */
    public function getSessionContainer()
    {
        return $this->session;
    }



}
