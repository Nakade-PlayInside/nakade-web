<?php
namespace Stats\Entity;

/**
 * AbstractPrize
 *
 * @package Stats\Entity
 */
abstract class AbstractPrize implements PrizeInterface
{
    protected $noGold=0;
    protected $noSilver=0;
    protected $noBronze=0;

    /**
     * @return $this
     */
    public function addBronze()
    {
        $this->noBronze++;
        return $this;
    }

    /**
     * @return int
     */
    public function getNoBronze()
    {
        return $this->noBronze;
    }

    /**
     * @return $this
     */
    public function addGold()
    {
        $this->noGold++;
        return $this;
    }

    /**
     * @return int
     */
    public function getNoGold()
    {
        return $this->noGold;
    }

    /**
     * @return $this
     */
    public function addSilver()
    {
        $this->noSilver++;
        return $this;
    }

    /**
     * @return int
     */
    public function getNoSilver()
    {
        return $this->noSilver;
    }

}