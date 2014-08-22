<?php
namespace Support\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SupportType
 *
 * @package Support\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="supportType")
 */
class SupportType
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="type", type="string", length=30, unique=true, nullable=false)
     */
    private $type;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


}