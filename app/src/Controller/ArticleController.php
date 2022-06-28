<?php
/**
 * Article controller.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Form\Type\ArticleType;
use App\Service\CommentServiceInterface;
use App\Service\ArticleServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ArticleController.
 */
#[Route('/article')]
class ArticleController extends AbstractController
{
    /**
     * Article service.
     */
    private ArticleServiceInterface $articleService;

    /**
     * Comment service.
     */
    private CommentServiceInterface $commentService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Construct.
     *
     * @param ArticleServiceInterface $articleService Article service
     * @param TranslatorInterface     $translator     Translator
     * @param CommentServiceInterface $commentService Comment service
     */
    public function __construct(ArticleServiceInterface $articleService, TranslatorInterface $translator, CommentServiceInterface $commentService)
    {
        $this->articleService = $articleService;
        $this->translator = $translator;
        $this->commentService = $commentService;
    }

    /**
     * Index action.
     *
     * @param Request $request
     *
     * @return Response
     */
    #[Route(name: 'article_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $filters = $this->getFilters($request);
        /** @var User $user */
        //  $user = $this->getUser();
        $pagination = $this->articleService->getPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('article/index.html.twig', ['pagination' => $pagination]);
        }

        return $this->render('article/index2.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Article $article Article entity
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'article_show', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    public function show(Article $article, Request $request): Response
    {
        $commentPagination = $this->commentService->getPaginatedList(
            $request->query->getInt('page', 1),
            $article
        );

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('article/show.html.twig', ['article' => $article, 'commentPagination' => $commentPagination]);
        }

        return $this->render('article/show2.html.twig', ['article' => $article, 'commentPagination' => $commentPagination]);
    }

    /**
     * Create action.
     *
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/create', name: 'article_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article, ['action' => $this->generateUrl('article_create')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleService->save($article);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Article $article Article entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'article_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article, [
            'method' => 'PUT',
            'action' => $this->generateUrl('article_edit', ['id' => $article->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleService->save($article);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Article $article Article entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'article_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Article $article): Response
    {
        $form = $this->createForm(FormType::class, $article, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('article_delete', ['id' => $article->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleService->delete($article);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/delete.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    /**
     * Get filters from request.
     *
     * @param Request $request HTTP request
     *
     * @return array<string, int> Array of filters
     *
     * @psalm-return array{category_id: int, tag_id: int, status_id: int}
     */
    private function getFilters(Request $request): array
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        return $filters;
    }
}
