<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Calculation;

use Nakade\Abstracts\AbstractTranslation;
use Season\Entity\EventTypeInterface;
use Season\Entity\League;
use League\Entity\Player;
use Stats\Entity\Certificate;
use Zend\I18n\Translator\Translator;

/**
 * Class CertificateFactory
 *
 * @package Stats\Calculation
 */
class CertificateFactory extends AbstractTranslation implements EventTypeInterface
{

    private $league;
    private $player;

    /**
     * @param Translator $translator
     * @param string $textDomain
     */
    public function __construct(Translator $translator, $textDomain)
    {
        $this->setTranslator($translator, $textDomain);
    }

    public function getCertificate(League $league, Player $player)
    {
        $this->league = $league;
        $this->player = $player;

        $data = array(
            'name' => $this->getPlayer()->getUser()->getCertificateName(),
            'tournamentInfo' => $this->getTournamentInfo(),
            'award' => $this->getAward()
        );

        $certificate = new Certificate();
        $certificate->exchangeArray($data);

        return $certificate;
    }

    /**
     * @return League
     */
    public function getLeague()
    {
        return $this->league;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    private function getAward()
    {
        $award = '';
        if ($this->getPlayer()->getPosition() >= 1 && $this->getPlayer()->getPosition()<=3) {
            $award = sprintf('%s. %s',
                $this->getPlayer()->getPosition(),
                $this->translate('Place')
            );
        }
        return $award;
    }

    /**
     * @return string
     */
    private function getTournamentInfo()
    {
        $info = sprintf('%s. %s %s %s',
            $this->getLeague()->getSeason()->getNumber(),
            $this->getLeague()->getSeason()->getAssociation()->getName(),
            $this->getTournamentType(),
            $this->getLeague()->getSeason()->getStartDate()->format('Y')
        );

        if ($this->isLeague()) {
            $info .= ' - ' . $this->getLeagueDetails();
        }
        return $info;
    }

    /**
     * @return string
     */
    private function getTournamentType()
    {
        $info = $this->translate('Tournament');
        if ($this->isLeague()) {
            $info = $this->translate('League');
        }

        return $info;
    }

    /**
     * @return string
     */
    private function getLeagueDetails()
    {
        $details = '';
        if ($this->isLeague()) {
            sprintf('%s. %s',
                $this->getLeague()->getNumber(),
                $this->translate('Division')
            );
        }

        return $details;
    }

    /**
     * @return bool
     */
    private function isLeague()
    {
        return $this->getLeague()->getSeason()->getAssociation()->getType()->getId() == self::TYPE_LEAGUE;
    }

}