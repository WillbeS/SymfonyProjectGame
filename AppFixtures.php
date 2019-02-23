<?php
//
//namespace AppBundle\DataFixtures;
//
//
//use AppBundle\Entity\GridCell;
//use AppBundle\Entity\Terrain;
//use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Common\Persistence\ObjectManager;
//
//class AppFixtures extends Fixture
//{
//    const MAP_SIZE = 100;
//
//    public function load(ObjectManager $manager)
//    {
//        $terrains = $manager->getRepository(Terrain::class)->findAll();
//        $terrainsWithRatio = [];
//
//        foreach ($terrains as $terrain) {
//            for ($i = 0; $i < $terrain->getRandomFactor(); $i++) {
//                $terrainsWithRatio[] = $terrain;
//            }
//        }
//        $max = count($terrainsWithRatio) - 1;
//
//        for ($row = 1; $row <= self::MAP_SIZE; $row++) {
//            for ($col = 1; $col <= self::MAP_SIZE; $col++) {
//                $terrain = $terrainsWithRatio[rand(0, $max)];
//                $grid = new GridCell();
//                $grid
//                    ->setRow($row)
//                    ->setCol($col)
//                    ->setTerrain($terrain);
//                $manager->persist($grid);
//            }
//        }
//
//        $manager->flush();
//    }
//}