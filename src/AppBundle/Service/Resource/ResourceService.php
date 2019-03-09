<?php

namespace AppBundle\Service\Resource;

use AppBundle\Entity\GameResource;
use AppBundle\Entity\ResourceType;
use AppBundle\Repository\ResourceTypeRepository;
use AppBundle\Service\App\GameNotificationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class ResourceService implements ResourceServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ResourceTypeRepository
     */
    private $resourceTypeRepo;


    public function __construct(EntityManagerInterface $em,
                                ResourceTypeRepository $resourceTypeRepository)
    {
        $this->em = $em;
        $this->resourceTypeRepo = $resourceTypeRepository;
    }


    public function getResourceType(string $name): ?ResourceType
    {
        /** @var ResourceType $resourceType */
        $resourceType = $this->resourceTypeRepo->findOneBy(['name' => $name]);

        return $resourceType;
    }

    public function updateTotal(GameResource $resource, float $amount)
    {
        $newTotal = $resource->getTotal() + $amount;

        if($newTotal < 0) {
            throw new GameNotificationException('Insufficient ' . $resource->getResourceType()->getName());
        }

        $resource
            ->setTotal($newTotal)
        ;
    }
}