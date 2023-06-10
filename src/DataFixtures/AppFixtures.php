<?php

/**
 * App Fixtures.
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AppFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class AppFixtures extends Fixture
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Manager
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
