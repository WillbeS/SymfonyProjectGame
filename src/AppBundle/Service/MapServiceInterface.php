<?php

namespace AppBundle\Service;


interface MapServiceInterface
{
    public function findAllByDistrict(int $district): array;

    public function findAvailableByDistrict(int $district): array;
}