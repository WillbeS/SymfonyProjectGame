<?php

namespace AppBundle\Entity;


interface PlatformUnitInterface
{
    public function getId(): ?int;

    public function getPlatform(): Platform;
}