<?php
/**
 * Article service.
 */

namespace App\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ArticleService.
 */
class ArticleService implements ArticleServiceInterface
{
    /**
     * Category service.
     */
    private CategoryServiceInterface $categoryService;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Tag service.
     */
    private TagServiceInterface $tagService;

    /**
     * Article repository.
     */
    private ArticleRepository $articleRepository;

    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService   Category service
     * @param PaginatorInterface       $paginator         Paginator
     * @param TagServiceInterface      $tagService        Tag service
     * @param ArticleRepository        $articleRepository Article repository
     */
    public function __construct(CategoryServiceInterface $categoryService, PaginatorInterface $paginator, TagServiceInterface $tagService, ArticleRepository $articleRepository)
    {
        $this->categoryService = $categoryService;
        $this->paginator = $paginator;
        $this->tagService = $tagService;
        $this->articleRepository = $articleRepository;
    }

    /**
     * Get paginated list.
     *
     * @param int                $page    Page number
     * @param array<string, int> $filters Filters array
     *
     * @return PaginationInterface<string, mixed> Paginated list
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate($this->articleRepository->queryAll($filters), $page);
    }

    /**
     * Save entity.
     *
     * @param Article $article Article entity
     */
    public function save(Article $article): void
    {
        $this->articleRepository->save($article);
    }

    /**
     * Delete entity.
     *
     * @param Article $article Article entity
     */
    public function delete(Article $article): void
    {
        $this->articleRepository->delete($article);
    }

    /**
     * Prepare filters for the articles list.
     *
     * @param array<string, int> $filters Raw filters from request
     *
     * @return array<string, object> Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (!empty($filters['category_id'])) {
            $category = $this->categoryService->findOneById($filters['category_id']);
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (!empty($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }
}
