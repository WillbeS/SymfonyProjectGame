<?php

namespace AppBundle\Service\Unit;


use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Platform;
use AppBundle\Entity\Unit;
use AppBundle\Repository\UnitRepository;
use AppBundle\Traits\AssertFound;
use Doctrine\ORM\EntityManagerInterface;

class UnitService implements UnitServiceInterface
{
    use AssertFound;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UnitRepository
     */
    private $unitRepository;

    /**
     * @var UnitTypeServiceInterface
     */
    private $unitTypeService;


    public function __construct(EntityManagerInterface $em,
                                UnitRepository $unitRepository,
                                UnitTypeServiceInterface $unitTypeService)
    {
        $this->em = $em;
        $this->unitRepository = $unitRepository;
        $this->unitTypeService = $unitTypeService;
    }

    public function getById(int $id): Unit
    {
        $unit = $this->unitRepository->find($id);
        $this->assertFound($unit);

        return $unit;
    }

    /**
     * @param Platform $platform
     * @return Unit[]
     */
    public function getAllByPlatform(Platform $platform): array
    {
        //TODO - decide
        //No need for this unless I plan to do a join or some special collection (key => value)
        return $this->unitRepository->findBy(['platform' => $platform]);
    }

    /**
     * @param Building $building
     * @return Unit[]
     */
    public function getAllByBuilding(Building $building): array
    {
        return $this->unitRepository->findBy(['building' => $building]);
    }


    public function updateUnitStatus(Platform $platform)
    {
        $units = $this->getAllByPlatform($platform);

        foreach ($units as $unit) {
            $isAvailable = $this->unitTypeService->isAvailable($unit->getUnitType(), $platform);

            if($unit->isAvailable() !== $isAvailable) {
                $unit->setIsAvailable($isAvailable);
            }
        }

        $this->em->flush();
    }
}