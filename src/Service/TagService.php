<?php
/**
 * Tag service.
 */

namespace App\Service;

use App\Repository\EventRepository;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TagService.
 */
class TagService implements TagServiceInterface
{
    /**
     * Tag repository.
     */
    private TagRepository $tagRepository;

    /**
     * Event repository.
     */
    private EventRepository $eventRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param TagRepository $tagRepository Tag repository
     * @param PaginatorInterface $paginator Paginator
     * @param EventRepository $eventRepository
     */
    public function __construct(TagRepository $tagRepository, PaginatorInterface $paginator, \App\Repository\EventRepository $eventRepository)
    {
        $this->tagRepository = $tagRepository;
        $this->paginator = $paginator;
        $this->eventRepository = $eventRepository;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->tagRepository->queryAll(),
            $page,
            TagRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Tag $tags Tag entity
     */
    public function save(Tag $tags): void
    {
        $this->tagRepository->save($tags);
    }

    /**
     * Delete entity.
     *
     * @param Tag $tags Tag entity
     */
    public function delete(Tag $tags): void
    {
        $this->tagRepository->delete($tags);
    }

    /**
     * Find by title.
     *
     * @param string $title Tag title
     *
     * @return Tag|null Tag entity
     */
    public function findOneByName(string $title): ?Tag
    {
        return $this->tagRepository->findOneByName($title);
    }

    /**
     * Find by id.
     *
     * @param int $id Tag id
     *
     * @return Tag|null Tag entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Tag
    {
        return $this->tagRepository->findOneById($id);
    }
}
