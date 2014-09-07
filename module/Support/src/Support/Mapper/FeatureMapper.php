<?php
namespace Support\Mapper;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Nakade\Pagination\ItemPagination;

/**
 * Class FeatureMapper
 *
 * @package Support\Mapper
 */
class FeatureMapper extends DefaultMapper
{

    /**
     * @return array
     */
    public function getFeatures()
    {
        return $this->getEntityManager()
            ->getRepository('Support\Entity\Feature')
            ->findAll();
    }

    /**
     * @param $featureId
     *
     * @return \Support\Entity\Feature
     */
    public function getFeatureById($featureId)
    {
        return $this->getEntityManager()
            ->getRepository('Support\Entity\Feature')
            ->find($featureId);
    }

}

