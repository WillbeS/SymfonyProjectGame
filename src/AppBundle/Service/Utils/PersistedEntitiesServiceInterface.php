<?php

namespace AppBundle\Service\Utils;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

interface PersistedEntitiesServiceInterface
{
    public function getAllPersisted(EntityManagerInterface $em): ArrayCollection;
}