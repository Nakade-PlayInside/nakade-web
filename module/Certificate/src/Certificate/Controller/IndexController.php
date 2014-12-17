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
        $pdf->setOption('filename', 'certificate'); // Triggers PDF download, automatically appends ".pdf"
        $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
        $pdf->setOption('paperOrientation', 'portrait'); // Defaults to "portrait"

        // To set view variables
        $pdf->setVariables(array(
            'winner' => 'Stephan Grohel-Lornemann',
            'award' => '2nd Place',
            'tournament' => '4. Nakade League 2015 - 2. Division'
        ));



       return $pdf;
       return new ViewModel(
            array(
                'winner' => 'Tina Maerz',
                'award' => '2nd Place',
                'tournament' => '4. Nakade League 2015 - 2. Division'

            )
        );
      //  return $pdf;
    }


}
