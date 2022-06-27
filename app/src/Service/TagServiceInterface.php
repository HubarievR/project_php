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
    public function save(Tag $tag): void;

    public function delete(Tag $tag): void;

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
