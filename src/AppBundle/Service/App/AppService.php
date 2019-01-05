<?php

namespace AppBundle\Service\App;


use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Service\Resource\ResourceServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class AppService implements AppServiceInterface
{
    const UDATE_INTERVAL = 60;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ResourceServiceInterface
     */
    private $resourceService;




    public function __construct(EntityManagerInterface $entityManager,
                                ResourceServiceInterface $resourceService)
    {
        $this->em = $entityManager;
        $this->resourceService = $resourceService;
    }

    public function getPlatformCurrentState(Platform $platform)
    {
        //todo - calculate income per hour here
    }

    public function updateTotalResource(Platform $platform)
    {
        /** @var GameResource $wood */
        $wood = $platform->getWood();
        $now = new \DateTime('now');
        $elapsedSeconds = $now->getTimestamp() - $wood->getUpdateTime()->getTimestamp();

        if ($elapsedSeconds >= self::UDATE_INTERVAL) {
            var_dump('Will recieve res');
        }


        var_dump($elapsedSeconds);
    }
}