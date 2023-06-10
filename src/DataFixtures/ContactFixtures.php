<?php
/**
 * Contact fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\Contact;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ContactFixtures.
 */
class ContactFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
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

        $this->createMany(50, 'contacts', function (int $i) {
            $contact = new Contact();
            $contact->setName($this->faker->sentence);
            $contact->setEmail($this->faker->email);
            $contact->setPhoneNumber($this->faker->phoneNumber);
            $contact->setNote($this->faker->sentence);

            /** @var array<array-key, Tag> $tags */
            $tags = $this->getRandomReferences(
                'tags',
                $this->faker->numberBetween(0, 5)
            );
            foreach ($tags as $tag) {
                $contact->addTag($tag);
            }

            /** @var User $author */
            $author = $this->getRandomReference('users');
            $contact->setAuthor($author);

            return $contact;
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
