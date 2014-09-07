<?php

namespace Support\Controller;

use Support\Entity\Feature;
use Support\Services\FormService;
use Zend\View\Model\ViewModel;

/**
 * Class FeatureController
 *
 * @package Application\Controller
 */
class FeatureController extends DefaultController
{
    const HOME = 'feature';

    /**
     * @return array
     */
    public function indexAction()
    {
        return new ViewModel(
            array(
                'features' => $this->getFeatureMapper()->getFeatures(),
            )
        );

    }

    /**
     * @return array
     */
    public function addAction()
    {
        /* @var $form \Support\Form\LeagueManagerForm */
        $form = $this->getForm(FormService::FEATURE_FORM);
        $feature = new Feature();
        $form->bindEntity($feature);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute(self::HOME);
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $manager = $form->getData();

                $this->getMapper()->save($manager);
                $this->flashMessenger()->addSuccessMessage('New Request added');

                return $this->redirect()->toRoute(self::HOME);
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }

        }

        return new ViewModel(
            array(
                'form'    => $form
            )
        );
    }

    /**
     * @return ViewModel
     */
    public function detailAction()
    {

        $featureId = (int) $this->params()->fromRoute('id', 0);

        return new ViewModel(
            array(
                'ticket' => $this->getFeatureMapper()->getFeatureById($featureId),
            )
        );
    }
}
