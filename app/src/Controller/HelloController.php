<?php
/**
 *Hello controller.
 */

namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HelloController.
 */
#[Route('/hello')]
class HelloController extends AbstractController
{
    /**
     * Index.
     *
     * @param string $name
     *
     * @return Response
     */
    #[Route('/{name}', name: 'hello_index', requirements: ['name' => '[a-zA-Z0-9]+'], defaults: ['name' => 'World'], methods: 'GET')]
    public function index(string $name): Response
    {
        return $this->render(
            'hello/index.html.twig',
            ['name' => $name]
        );
    }
}
