<?php

namespace App\Controller\Cms;

use App\Entity\Tag;
use App\Form\TagType;
use App\Library\Controller\BaseController;
use App\Service\Cms\TagService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/etiquetas", name="tag_")
 * @IsGranted("ROLE_EDITOR")
 */
class TagController extends BaseController
{
    const LIST_LIMIT = 10;

    private $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
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

        $posts = $this->tagService->getAll($filter, $page, $limit, $sort, $dir);
        $paginationData = $this->getPaginationData($request, $posts, $page, $limit);

        return $this->render('cms/tag/index.html.twig', array_merge(
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
        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/tag/new.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                $this->tagService->create($tag);

                $this->addFlash('app_success', '¡Etiqueta creada con éxito!');

                return $this->redirectToRoute('cms_tag_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de crear la etiqueta');
            }
        }

        return $this->render('cms/tag/new.html.twig', [
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
        $category = $this->tagService->get($id);
        if (!$category instanceof Tag) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(TagType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/tag/edit.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                $this->tagService->edit($category);

                $this->addFlash('app_success', '¡Etiqueta editada con éxito!');

                return $this->redirectToRoute('cms_tag_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de editar la etiqueta');
            }
        }

        return $this->render('cms/tag/edit.html.twig', [
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
        $tag = $this->tagService->get($id);
        if (!$tag instanceof Tag) {
            throw new NotFoundHttpException();
        }

        try {
            $this->tagService->remove($tag);
            $this->addFlash('app_success', '¡Etiqueta eliminada!');
        } catch (\Exception $e) {
            $this->addFlash('app_error', 'Hubo un error a la hora de eliminar la etiqueta');
        }

        return $this->redirectToRoute('cms_tag_index');
    }
}
