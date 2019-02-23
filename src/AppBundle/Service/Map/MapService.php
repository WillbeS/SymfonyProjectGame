<?php

namespace AppBundle\Service\Map;


use AppBundle\Entity\GridCell;
use AppBundle\Repository\GridRepository;

class MapService implements MapServiceInterface
{
    const NEW_SETTLEMENT_DISTRICT = 1;
    const DISTRICT_SIZE = 10;
    const MAP_SIZE = 50;

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

        $result = $this->gridRepository->findByCoords($x1, $x2, $y1, $y2);

        return $result;
    }

    public function findAvailableGridCell() : ?GridCell
    {
        return $this
            ->getRandomGridCell($this
                ->findAvailableByDistrict(self::NEW_SETTLEMENT_DISTRICT));
    }

    public function findAvailableByDistrict(int $district): array
    {
        [$x1, $x2, $y1, $y2] = $this->getRangeByDistrict($district);

        $cells = $this->gridRepository->findByCoords($x1, $x2, $y1, $y2);
        $available = [];

        foreach ($cells as  $cell) {
            /** @var GridCell $cell */
            if ((null === $cell->getPlatform()) && 'grass' === $cell->getTerrain()->getName()) {
                $available[] = $cell;
            }
        }

        return $available;
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

    /**
     * @param array $grid
     * @return GridCell
     */
    private function getRandomGridCell(array $grid): GridCell
    {
        $max = count($grid) - 1;
        return $grid[rand(0, $max)];
    }
}