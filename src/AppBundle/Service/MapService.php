<?php

namespace AppBundle\Service;


use AppBundle\Entity\Grid;
use AppBundle\Repository\GridRepository;

class MapService implements MapServiceInterface
{
    const DISTRICT_SIZE = 5;

    const MAP_SIZE = 100;

    /**
     * @var GridRepository
     */
    private $gridRepository;

    /**
     * MapService constructor.
     * @param GridRepository $gridRepository
     */
    public function __construct(GridRepository $gridRepository)
    {
        $this->gridRepository = $gridRepository;
    }


    public function findAllByDistrict(int $district): array
    {
        [$x1, $x2, $y1, $y2] = $this->getRangeByDistrict($district);

        return $this->gridRepository->findByCoords($x1, $x2, $y1, $y2);
    }

    public function findAvailableByDistrict(int $district): array
    {
        [$x1, $x2, $y1, $y2] = $this->getRangeByDistrict($district);

        $cells = $this->gridRepository->findByCoords($x1, $x2, $y1, $y2);

        return array_filter($cells, function (Grid $cell) {
            return (null === $cell->getPlatform()) && 'grass' === $cell->getTerrain()->getName();
        });
    }

    private function getRangeByDistrict(int $district) {
        $totalPerRow = self::MAP_SIZE / self::DISTRICT_SIZE;
        $row = ceil($district / $totalPerRow);
        $col = $district % $totalPerRow;
        $col = $col !== 0 ? $col : $totalPerRow;

        $y1 = ($row - 1) * self::DISTRICT_SIZE + 1;
        $y2 = $row * self::DISTRICT_SIZE;
        $x1 = ($col - 1) * self::DISTRICT_SIZE + 1;
        $x2 = $col * self::DISTRICT_SIZE;

        return [$x1, $x2, $y1, $y2];
    }
}