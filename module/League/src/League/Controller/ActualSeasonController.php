<?php
namespace League\Controller;

use Nakade\Abstracts\AbstractController;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use User\Entity\User;

/**
 * League tables and schedules of the actual season.
 * Top league table is presented by the default action index.
 * ActionSeasonServiceFactory is needed to be set.
 *
 * @author Holger Maerz <holger@nakade.de>
 */
class ActualSeasonController extends AbstractController
{

    /**
    * Default action showing up the Top League table
    * in a short and compact version. This can be used as a widget.
    *
    * @return array|ViewModel
    */
    public function indexAction()
    {

        return new ViewModel(
            array(
              'title'     => $this->getService()->getTitle(),
              'table'     => $this->getService()->getTopLeagueTable()

            )
        );
    }

    /**
    * Shows actual matchplan of a user and his results.
    * If user is not in  a league, the top league schedule
    * is shown.
    *
    * @return array|ViewModel
    */
    public function scheduleAction()
    {
       $uid = $this->getUserId();

       return new ViewModel(
           array(
              'title'   => $this->getService()->getScheduleTitle($uid),
              'matches' => $this->getService()->getSchedule($uid),
           )
       );
    }

    /**
     * @return ViewModel
     */
    public function myScheduleAction()
    {
        $uid = $this->getUserId();

        return new ViewModel(
            array(
                'userId' =>  $uid,
                'title'   => $this->getService()->getMyScheduleTitle($uid),
                'matches' => $this->getService()->getMySchedule($uid),
            )
        );
    }

    /**
    * Shows the user's league table. If user is not in a league, the
    * Top league is shown instead. The Table is sortable.
    *
    * @return array|ViewModel
    */
    public function tableAction()
    {

       $uid = $this->getUserId();

        //sorting the table
       $sorting  = $this->params()->fromRoute('sort', null);

       return new ViewModel(
           array(
              'table'       => $this->getService()
                                    ->getLeagueTable($uid, $sorting),
              'tiebreakers' => $this->getService()
                                    ->getTiebreakerNames(),
              'title'       => $this->getService()
                                    ->getTableTitle($uid),

           )
       );
    }

    public function iCalAction()
    {

        $fileName = 'myNakade.iCal';

        // SEQUENCE: 0++
        // UNIQUE Id similar for updating
        $fileContents = "BEGIN:VCALENDAR" . PHP_EOL .
        "VERSION:2.0" . PHP_EOL .
        "PRODID: http://www.nakade.de" . PHP_EOL .
        "CALSCALE:GREGORIAN" . PHP_EOL .
        "METHOD:PUBLISH" . PHP_EOL .
        "BEGIN:VEVENT" . PHP_EOL .
        "DTSTART: 20140520T140000" . PHP_EOL .
        "DTEND: 20140520T170000" . PHP_EOL .
        "DTSTAMP:" . date('Ymd').'T'.date('His') . PHP_EOL .
        "UID:" . uniqid() . PHP_EOL .
        "LOCATION: Kiseido Go Server" . PHP_EOL .
        "DESCRIPTION: My Match vs Tina M., KGS Name: TinaGo. My Color is White. 60min, 15/10, 7.5 Komi" . PHP_EOL .
        "URL;VALUE=URI:nakade.de"  . PHP_EOL .
        "SUMMARY: Nakade League Match vs Tina M." . PHP_EOL .
        "CATEGORIES:GO" . PHP_EOL .
        "ORGANIZER:mailto:holger@nakade.de" . PHP_EOL .
        "END:VEVENT" . PHP_EOL .
        "END:VCALENDAR" . PHP_EOL;


        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'text/calendar; charset=utf-8')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        $response  = new \Zend\Http\Response\Stream();
        $response->setStatusCode(200);
        $response->setHeaders($headers);

        print $fileContents;
        return $response;

    }

    /**
     * @return \Zend\Http\Response | int
     */
    private function getUserId()
    {
        $user = $this->identity();

        if (is_null($user)) {
            return $this->redirect()->toRoute('login');
        }

        return $user->getId();
    }

}
