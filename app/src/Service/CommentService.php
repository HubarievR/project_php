<?php
/**
 * Comment service.
 */

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Article;
use App\Repository\CommentRepository;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CommentService.
 */
class CommentService implements CommentServiceInterface
{
    /**
     * Comment repository.
     */
    private CommentRepository $commentRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Article repository.
     */
    private ArticleRepository $articleRepository;

    /**
     * Constructor.
     *
     * @param CommentRepository  $commentRepository Comment repository
     * @param PaginatorInterface $paginator         Paginator
     * @param ArticleRepository  $articleRepository Article repository
     */
    public function __construct(CommentRepository $commentRepository, PaginatorInterface $paginator, ArticleRepository $articleRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
        $this->articleRepository = $articleRepository;
    }

    /**
     * Get paginated list.
     *
     * @param int     $page    Page number
     * @param Article $article Article entity
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, Article $article): PaginationInterface
    {
        return $this->paginator->paginate($this->commentRepository->queryAll($article), $page);
    }

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }

    /**
     * Save entity.
     *
     * @param Comment $comment Comment entity
     */
    public function save(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }
}
