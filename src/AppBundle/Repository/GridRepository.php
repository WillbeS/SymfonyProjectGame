<?php

namespace AppBundle\Repository;
use AppBundle\Entity\GridCell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


class GridRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GridCell::class);
    }

    public function findByIdRange($start, $end)
    {
        $q = $this->createQueryBuilder('cell')
            ->where('cell.id > :startId')
            ->andWhere('cell.id < :endId')
            ->setParameter('startId', $start)
            ->setParameter('endId', $end)
            ->getQuery();

        return $q->getResult();
    }

    public function findByCoords($x1, $x2, $y1, $y2)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.terrain', 't')
            ->leftJoin('c.platform', 'p')
            ->addSelect('t')
            ->addSelect('p')
            ->where('c.col >= :x1')
            ->andWhere('c.col <= :x2')
            ->andWhere('c.row >= :y1')
            ->andWhere('c.row <= :y2')
            ->setParameter('x1', $x1)
            ->setParameter('x2', $x2)
            ->setParameter('y1', $y1)
            ->setParameter('y2', $y2)
            ->addOrderBy('c.row', 'ASC')
            ->addOrderBy('c.col', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
