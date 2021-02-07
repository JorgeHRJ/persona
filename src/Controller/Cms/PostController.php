<?php

namespace App\Controller\Cms;

use App\Entity\Post;
use App\Form\PostType;
use App\Library\Controller\BaseController;
use App\Service\Cms\PostService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/articulos", name="post_")
 * @IsGranted("ROLE_EDITOR")
 */
class PostController extends BaseController
{
    const LIST_LIMIT = 10;

    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @Route("/", name="index")
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        list($page, $limit, $sort, $dir, $filter) = $this->handleIndexRequest($request, self::LIST_LIMIT);

        $posts = $this->postService->getAll($filter, $page, $limit, $sort, $dir);
        $paginationData = $this->getPaginationData($request, $posts, $page, $limit);

        return $this->render('cms/post/index.html.twig', array_merge(
            $posts,
            [
                'sort' => $request->query->get('sort'),
                'dir' => $request->query->get('dir'),
                'paginationData' => $paginationData,
                'params' => $request->query->all()
            ]
        ));
    }

    /**
     * @Route("/nuevo", methods="GET|POST", name="new")
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/post/new.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                $post->setAuthor($this->getUserInstance());

                $this->postService->create($post);

                $this->addFlash('app_success', '¡Artículo creado con éxito!');

                return $this->redirectToRoute('cms_post_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de crear el artículo');
            }
        }

        return $this->render('cms/post/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/editar/{id}", methods="GET|POST", name="edit")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        $post = $this->postService->get($id);
        if (!$post instanceof Post) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/post/edit.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                $this->postService->edit($post);

                $this->addFlash('app_success', '¡Artículo editado con éxito!');

                return $this->redirectToRoute('cms_post_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de editar el artículo');
            }
        }

        return $this->render('cms/post/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/eliminar/{id}", name="remove")
     *
     * @param int $id
     * @return Response
     */
    public function remove(int $id): Response
    {
        $post = $this->postService->get($id);
        if (!$post instanceof Post) {
            throw new NotFoundHttpException();
        }

        try {
            $this->postService->remove($post);
            $this->addFlash('app_success', '¡Artículo eliminado!');
        } catch (\Exception $e) {
            $this->addFlash('app_error', 'Hubo un error a la hora de eliminar el artículo');
        }

        return $this->redirectToRoute('cms_post_index');
    }
}
