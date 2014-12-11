<?php
namespace Stats\Entity;
use Nakade\TournamentInterface;

/**
 * AbstractPrize
 *
 * @package Stats\Entity
 */
abstract class AbstractPrize implements PrizeInterface
{
    protected $gold = array();
    protected $silver = array();
    protected $bronze = array();

    /**
     * @param TournamentInterface $tournament
     *
     * @return $this
     */
    public function addBronze(TournamentInterface $tournament)
    {
        $this->bronze[] = $tournament;
        return $this;
    }

    /**
     * @return array
     */
    public function getBronze()
    {
        return $this->bronze;
    }

    /**
     * @return int
     */
    public function getNoBronze()
    {
        return count($this->bronze);
    }

    /**
     * @param TournamentInterface $tournament
     *
     * @return $this
     */
    public function addGold(TournamentInterface $tournament)
    {
        $this->gold[] = $tournament;
        return $this;
    }

    /**
     * @return array
     */
    public function getGold()
    {
        return $this->gold;
    }

    /**
     * @return int
     */
    public function getNoGold()
    {
        return count($this->gold);
    }

    /**
     * @param TournamentInterface $tournament
     *
     * @return $this
     */
    public function addSilver(TournamentInterface $tournament)
    {
        $this->silver[] = $tournament;
        return $this;
    }

    /**
     * @return array
     */
    public function getSilver()
    {
        return $this->silver;
    }

    /**
     * @return int
     */
    public function getNoSilver()
    {
        return count($this->silver);
    }

}