<?php
/**
 * Tag Service Interface.
 */

namespace App\Service;

use App\Entity\Tag;

/**
 * Interface TagServiceInterface.
 */
interface TagServiceInterface
{
    /**
     * @param Tag $tag
     */
    public function save(Tag $tag): void;

    /**
     * @param Tag $tag
     */
    public function delete(Tag $tag): void;

    /**
     * @param Tag $tag
     *
     * @return bool
     */
    public function canBeDeleted(Tag $tag): bool;

    /**
     * Find by title.
     *
     * @param string $title Tag title
     *
     * @return Tag|null Tag entity
     */
    public function findOneByTitle(string $title): ?Tag;
}
