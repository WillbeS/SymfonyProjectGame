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

    public function findByCoords($x1, $x2, $y1, $y2, $criteria = null)
    {
        $q = $this->createQueryBuilder('cell')
            ->where('cell.col > :x1')
            ->andWhere('cell.col < :x2')
            ->andWhere('cell.row > :y1')
            ->andWhere('cell.row < :y2')
            ->setParameter('x1', $x1 - 1)
            ->setParameter('x2', $x2 + 1)
            ->setParameter('y1', $y1 - 1)
            ->setParameter('y2', $y2 + 1)
            ->addOrderBy('cell.row', 'ASC')
            ->addOrderBy('cell.col', 'ASC')
            ->getQuery();

        return $q->getResult();
    }
}
