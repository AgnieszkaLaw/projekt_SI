<?php
/**
 * Event fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class EventFixtures.
 */
class EventFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(50, 'events', function (int $i) {
            $event = new Event();
            $event->setName($this->faker->sentence);
            $event->setNote($this->faker->sentence);
            $event->setStartDate(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-5 days', '+50 days')
                )
            );
            $event->setEndDate(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-5 days', '+70 days')
                )
            );
            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $event->setCategory($category);

            /** @var array<array-key, Tag> $tags */
            $tags = $this->getRandomReferences(
                'tags',
                $this->faker->numberBetween(0, 5)
            );
            foreach ($tags as $tag) {
                $event->addTag($tag);
            }

            /** @var User $author */
            $author = $this->getRandomReference('users');
            $event->setAuthor($author);

            return $event;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, TagFixtures::class, UserFixtures::class];
    }
}
