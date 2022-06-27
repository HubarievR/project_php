<?php
/**
 * Category service interface.
 */

namespace App\Service;

use App\Entity\Category;

/**
 * Interface CategoryServiceInterface.
 */
interface CategoryServiceInterface
{
    public function save(Category $category): void;

    public function delete(Category $category): void;

    public function canBeDeleted(Category $category): bool;

    /**
     * Find by title.
     *
     * @param string $title Category title
     *
     * @return Category|null Category entity
     */
    public function findOneByTitle(string $title): ?Category;
}
