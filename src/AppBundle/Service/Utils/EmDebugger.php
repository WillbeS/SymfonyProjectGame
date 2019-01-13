<?php

namespace AppBundle\Service\Utils;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class EmDebugger implements EmDebuggerInterface
{
    public function getAllPersisted(EntityManagerInterface $em): ArrayCollection
    {
        $result = new ArrayCollection();
        $persistedEntities = $em->getUnitOfWork()->getScheduledEntityInsertions();

        foreach ($persistedEntities as $pe) {
            $className = get_class($pe);
            if(!$result->containsKey($className)) {
                $result[$className] = new ArrayCollection();
            }

            $result[$className]->add($pe);
        }

        return $result;
    }

    public function getAllPersistedBackUp(EntityManagerInterface $em): array
    {
        $result = [];
        $persistedEntities = $em->getUnitOfWork()->getScheduledEntityInsertions();
        foreach ($persistedEntities as $pe) {
            $result[get_class($pe)] = $pe;
        }

        return $result;
    }
}