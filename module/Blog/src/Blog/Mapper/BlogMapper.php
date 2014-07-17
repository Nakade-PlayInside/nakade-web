<?php
namespace Blog\Mapper;

use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\Query\Expr\Join;
use \Doctrine\ORM\Query;

/**
 * Class BlogMapper
 *
 * @package Blog\Mapper
 */
class BlogMapper extends AbstractMapper
{

    /**
     * @param int $id
     *
     * @return \Blog\Entity\Post
     */
    public function getPostById($id)
    {
        return $this->getEntityManager()
            ->getRepository('Blog\Entity\Post')
            ->find($id);
    }

    /**
     * @return array
     */
    public function getAllPublishedPosts()
    {
        return $this->getEntityManager()
            ->getRepository('Blog\Entity\Post')
            ->findBy(array('post_status' => 'publish'));
    }

    /**
     * @return array
     */
    public function getLatestPosts()
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Blog');
        $qb->select('b')
            ->from('Blog\Entity\Post', 'b')
            ->where('b.status = :status')
            ->andWhere('b.type = :type')
            ->setParameter('status', 'publish')
            ->setParameter('type', 'post')
            ->addOrderBy('b.date', 'DESC')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

}

