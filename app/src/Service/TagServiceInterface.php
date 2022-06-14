<?php

namespace App\Service;

use App\Entity\Tag;

interface TagServiceInterface
{
    public function save(Tag $tag): void;

    public function delete(Tag $tag): void;

    /**
     * Can Tag be deleted?
     *
     * @param Tag $tag Tag entity
     *
     * @return bool Result
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
