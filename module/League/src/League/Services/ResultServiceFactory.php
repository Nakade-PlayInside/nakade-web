<?php
//module/Authentication/src/Authentication/Services/AuthServiceFactory.php
namespace League\Services;

use League\Statistics\Results as RESULT;
use League\Statistics\Tiebreaker\HahnPoints as HAHN;
use League\Form\WinnerFilter;
use League\Form\PointsFilter;

use Nakade\Abstracts\AbstractService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;


/**
 * Factory for creating the Zend Authentication Services. Using customized
 * Adapter and Storage instances.
 *
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class ResultServiceFactory extends AbstractService
{

    protected $_result_form;
    protected $_match;


    public function getResultForm()
    {
        return $this->_result_form;
    }
    public function setResultForm($form)
    {
        $this->_result_form=$form;
        return $this;
    }

    public function getMatch()
    {
        return $this->_match;
    }

    public function setMatch($match)
    {
        $this->_match=$match;
        return $this;
    }


    /**
     * Creating Zend Authentication Services for logIn and logOut action.
     * Making use of customized adapters for more action as by default.
     * Integration of optional translation feature (i18N)
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return \Zend\Authentication\AuthenticationService
     * @throws RuntimeException
     *
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }


        //configuration
        $textDomain = isset($config['League']['text_domain']) ?
            $config['League']['text_domain'] : null;

        //optional translator
        $translator = $services->get('translator');
        $this->setTranslator($translator, $textDomain);

        $this->setMapperFactory($services->get('League\Factory\MapperFactory'));
        $this->_result_form = $services->get('result_form');

        return $this;

    }

    public function getActualSeason()
    {
        $season = $this->getMapper('season')->getActualSeason();
        if(null===$season) {
            $season = $this->getMapper('season')->getLastSeason();
        }

        return $season;
    }


    /**
     * shows all open results in a season.
     * A link is provided for each match to enter a result.
     * @return mixed
     */
    public function getMyResult($uid) {

        $season = $this->getActualSeason();
        if($season==null)
            return null;

        return $this->getMapper('match')->getMyResults($season->getId(), $uid);
    }


    /**
     * shows all open results in a season.
     * A link is provided for each match to enter a result.
     * @return mixed
     */
    public function getMyOpenResult($uid) {

        $season = $this->getActualSeason();
        if($season==null)
            return null;

        return $this->getMapper('match')
                    ->getMyOpenResults( $season->getId(), $uid );
    }


    /**
     * shows all open results in a season.
     * A link is provided for each match to enter a result.
     * @return mixed
     */
    public function getOpenResult() {

        $season = $this->getActualSeason();
        if($season==null)
            return null;

        return $this->getMapper('match')
                    ->getAllOpenResults($season->getId());
    }



    public function prepareFormForValidation($form, $data)
    {
        $form->setData($data);

        //set filter and validator dependant on values
        $this->setResultFormValidators($form);


    }

    /**
     * Returns a result form.
     * Before returning it, match data and the ID are set.
     *
     * @param int $pid
     * @return Form
     */
    public function setResultFormValues($pid) {

       //match to enter a result
        $this->_match = $this->getMapper('match')->getMatchById($pid);

        //form
        $form = $this->getResultForm();
        $form->setPairing($this->_match);
        $form->setId($pid);
        $form->init();
        $this->setResultForm($form);

        return $form;
    }

    /**
     * Activates the filter and validator for points
     * or winner in the form provided dependant on the result input.
     * This has to be set before validation.
     *
     * @param type $form
     */
    public function setResultFormValidators(&$form)
    {
            $result = $form->get('result')->getValue();

            switch($result) {

                case RESULT::BYPOINTS:

                        $filter  = new PointsFilter();
                        break;

                case RESULT::DRAW:
                case RESULT::SUSPENDED:

                        $filter  = new WinnerFilter();
                        break;

                default: return;
            }

            $form->setInputFilter($filter);


    }


    /**
     * processing and save match data to database
     *
     * @param Form $form
     * @return boolean
     */
    public function processResultData($form)
    {

        if($form->isValid()) {

            $validatedData = $form->getData();
            $this->filterResultWinner($validatedData);
            $this->filterResultPoints($validatedData);

            $match = $this->getMapper('match')
                          ->getMatchById($validatedData['pid']);

            $match->populate($validatedData);
            $this->getMapper('match')->save($match);
            return true;
        }

        return false;
    }

    /**
     * unset the winner if the match is a draw or suspended
     * @param array $validatedData
     */
    protected function filterResultWinner(&$validatedData)
    {
        switch($validatedData['result']) {

           case RESULT::DRAW:
           case RESULT::SUSPENDED:
                    $validatedData['winner']=null;

        }
    }

    /**
     * Unset points if result is not by points. If the offset
     * points are exceeded, result is set to resignation.
     *
     * @param array $validatedData
     */
    protected function filterResultPoints(&$validatedData)
    {

        switch($validatedData['result']) {

           case RESULT::BYPOINTS:

               if($validatedData['points']>=HAHN::OFFSET_POINTS) {
                   $validatedData['points']='';
                   $validatedData['result']=1;
               }
               break;

           default:

               $validatedData['points']='';
               break;
        }

    }

}


