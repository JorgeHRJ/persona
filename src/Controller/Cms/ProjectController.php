<?php

namespace App\Controller\Cms;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Library\Controller\BaseController;
use App\Service\Cms\ProjectService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/proyectos", name="project_")
 * @IsGranted("ROLE_ADMIN")
 */
class ProjectController extends BaseController
{
    const LIST_LIMIT = 10;

    private $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
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

        $posts = $this->projectService->getAll($filter, $page, $limit, $sort, $dir);
        $paginationData = $this->getPaginationData($request, $posts, $page, $limit);

        return $this->render('cms/project/index.html.twig', array_merge(
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
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/project/new.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                $this->projectService->create($project);

                $this->addFlash('app_success', '¡Proyecto creado con éxito!');

                return $this->redirectToRoute('cms_project_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de crear el proyecto');
            }
        }

        return $this->render('cms/project/new.html.twig', [
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
        $project = $this->projectService->get($id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/project/edit.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                $this->projectService->edit($project);

                $this->addFlash('app_success', '¡Proyecto editado con éxito!');

                return $this->redirectToRoute('cms_project_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de editar el proyecto');
            }
        }

        return $this->render('cms/project/edit.html.twig', [
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
        $project = $this->projectService->get($id);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException();
        }

        try {
            $this->projectService->remove($project);
            $this->addFlash('app_success', '¡Proyecto eliminad0!');
        } catch (\Exception $e) {
            $this->addFlash('app_error', 'Hubo un error a la hora de eliminar el proyecto');
        }

        return $this->redirectToRoute('cms_project_index');
    }
}
