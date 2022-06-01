<?php

namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\CategoryRepository;

class CategoryService implements CategoryServiceInterface{

    private PaginatorInterface $paginator;

    public function save(Category $category): void
    {
        if ($category->getId() == null) {
            $category->setCreatedAt(new \DateTimeImmutable());
        }
        $category->setUpdatedAt(new \DateTimeImmutable());

        $this->categoryRepository->save($category);
    }

    public function __construct(CategoryRepository $categoryRepository, PaginatorInterface $paginator)
    {
        $this->categoryRepository = $categoryRepository;
        $this->paginator = $paginator;
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}