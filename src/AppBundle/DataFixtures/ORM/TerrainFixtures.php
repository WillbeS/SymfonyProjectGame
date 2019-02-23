<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Terrain;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TerrainFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (Terrain::TERRAIN_TYPES as $name => $randomFactor) {
            $terrain = (new Terrain())
                ->setName($name)
                ->setRandomFactor($randomFactor);

            $manager->persist($terrain);
            $this->addReference($name, $terrain);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 100;
    }
}