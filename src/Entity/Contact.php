<?php

/**
 * Contact entity.
 */

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Contact.
 */
#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: 'contacts')]
class Contact
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Name.
     */
    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 191, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $note = null;

    /**
     * Tags.
     *
     * @var ArrayCollection<int, Tag>
     */
    #[Assert\Valid]
    #[ORM\ManyToMany(targetEntity: Tag::class, fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'contacts_tags')]
    private $tags;

    /**
     * Author.
     */
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(User::class)]
    private ?User $author;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for name.
     *
     * @param string|null $name Name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for email.
     *
     * @return string|null Email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for email.
     *
     * @param string|null $email Email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * Getter for phoneNumber.
     *
     * @return string|null PhoneNumber
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * Setter for phoneNumber.
     *
     * @param string|null $phoneNumber PhoneNumber
     */
    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Getter for note.
     *
     * @return string|null Note
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * Setter for note.
     *
     * @param string|null $note Note
     */
    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

    /**
     * Getter for tags.
     *
     * @return Collection<int, Tag> Tags collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add tag.
     *
     * @param Tag $tag Tag entity
     */
    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }

    /**
     * Remove tag.
     *
     * @param Tag $tag Tag entity
     */
    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Getter for author.
     *
     * @return string|null Author
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for author.
     *
     * @param string|null $author Author
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }
}
