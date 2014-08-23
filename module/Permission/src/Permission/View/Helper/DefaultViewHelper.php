<?php
namespace Permission\View\Helper;

use Nakade\Abstracts\AbstractViewHelper;
use \Permission\Entity\RoleInterface;

/**
 * Class DefaultViewHelper
 *
 * @package Permission\View\Helper
 */
class DefaultViewHelper extends AbstractViewHelper implements RoleInterface
{
    protected  $voterService;

    /**
     * @return \Permission\Services\VoterService
     */
    protected function getVoterService()
    {
        if (is_null($this->voterService)) {
            $this->voterService = $this->getService('Permission\Services\VoterService');
        }
        return $this->voterService;
    }


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

}
