<?php

namespace App\Controller\Cms;

use App\Form\CategoryType;
use App\Library\Controller\BaseController;
use App\Service\Cms\CategoryService;
use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorias", name="category_")
 * @IsGranted("ROLE_EDITOR")
 */
class CategoryController extends BaseController
{
    const LIST_LIMIT = 10;

    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
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

        $posts = $this->categoryService->getAll($filter, $page, $limit, $sort, $dir);
        $paginationData = $this->getPaginationData($request, $posts, $page, $limit);

        return $this->render('cms/category/index.html.twig', array_merge(
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
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/category/new.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                $this->categoryService->create($category);

                $this->addFlash('app_success', '¡Categoría creada con éxito!');

                return $this->redirectToRoute('cms_category_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de crear la categoría');
            }
        }

        return $this->render('cms/category/new.html.twig', [
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
        $category = $this->categoryService->get($id);
        if (!$category instanceof Category) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/category/edit.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                $this->categoryService->edit($category);

                $this->addFlash('app_success', '¡Categoría editada con éxito!');

                return $this->redirectToRoute('cms_category_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de editar la categoría');
            }
        }

        return $this->render('cms/category/edit.html.twig', [
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
        $category = $this->categoryService->get($id);
        if (!$category instanceof Category) {
            throw new NotFoundHttpException();
        }

        try {
            $this->categoryService->remove($category);
            $this->addFlash('app_success', '¡Categoría eliminada!');
        } catch (\Exception $e) {
            $this->addFlash('app_error', 'Hubo un error a la hora de eliminar la categoría');
        }

        return $this->redirectToRoute('cms_category_index');
    }
}
