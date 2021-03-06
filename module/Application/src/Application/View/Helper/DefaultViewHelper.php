<?php
namespace Application\View\Helper;

use Nakade\Abstracts\AbstractViewHelper;

/**
 * Class DefaultViewHelper
 *
 * @package Application\View\Helper
 */
class DefaultViewHelper extends AbstractViewHelper
{
    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    protected function getService($name)
    {
        /* @var $view \Zend\View\Renderer\PhpRenderer */
        $view = $this->getView();

        $locator = $view->getHelperPluginManager()->getServiceLocator();

        if (!$locator->has($name)) {
            throw new \RuntimeException(
                sprintf('Service %s not found.', $name)
            );

        }

        return  $locator->get($name);
    }

    /**
     * @return \User\Entity\User
     */
    public function getIdentity()
    {
        /* @var $view \Zend\View\Renderer\PhpRenderer */
        $view = $this->getView();
        return $view->identity();
    }
}
