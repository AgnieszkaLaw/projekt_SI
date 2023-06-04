<?php
/**
 * Tag service interface.
 */

namespace App\Service;

use App\Entity\Tag;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TagServiceInterface.
 */
interface TagServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Tag $tags Tag entity
     */
    public function save(Tag $tags): void;

    /**
     * Delete entity.
     *
     * @param Tag $tags Tag entity
     */
    public function delete(Tag $tags): void;

    /**
     * Find by title.
     *
     * @param string $title Tag title
     *
     * @return Tag|null Tag entity
     */
    public function findOneByName(string $title): ?Tag;

    public function findOneById(int $id): ?Tag;

}

