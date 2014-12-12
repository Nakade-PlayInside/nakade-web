<?php
namespace Certificate\Controller;

use Nakade\Abstracts\AbstractController;
use DOMPDFModule\View\Model\PdfModel;
use Zend\View\Model\ViewModel;
/**
 *
 * @package Certificate\Controller
 */
class IndexController extends AbstractController
{

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $pdf = new PdfModel();
        $pdf->setOption('filename', 'monthly-report'); // Triggers PDF download, automatically appends ".pdf"
        $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
        $pdf->setOption('paperOrientation', 'portrait'); // Defaults to "portrait"

        // To set view variables
        $pdf->setVariables(array(
            'message' => 'Hello'
        ));

        return $pdf;
    }


}
