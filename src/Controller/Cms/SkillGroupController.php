<?php

namespace App\Controller\Cms;

use App\Form\SkillGroupType;
use App\Library\Controller\BaseController;
use App\Service\Cms\SkillGroupService;
use App\Entity\SkillGroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/habilidades", name="skillgroup_")
 * @IsGranted("ROLE_ADMIN")
 */
class SkillGroupController extends BaseController
{
    const LIST_LIMIT = 10;

    private $groupService;

    public function __construct(SkillGroupService $groupService)
    {
        $this->groupService = $groupService;
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

        $groups = $this->groupService->getAll($filter, $page, $limit, $sort, $dir);
        $paginationData = $this->getPaginationData($request, $groups, $page, $limit);

        return $this->render('cms/skillgroup/index.html.twig', array_merge(
            $groups,
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
        $category = new SkillGroup();

        $form = $this->createForm(SkillGroupType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/skillgroup/new.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                $this->groupService->create($category);

                $this->addFlash('app_success', '¡Categoría creada con éxito!');

                return $this->redirectToRoute('cms_skillgroup_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de crear la categoría');
            }
        }

        return $this->render('cms/skillgroup/new.html.twig', [
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
        $group = $this->groupService->get($id);
        if (!$group instanceof SkillGroup) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(SkillGroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/skillgroup/edit.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                $this->groupService->edit($group);

                $this->addFlash('app_success', '¡Categoría editada con éxito!');

                return $this->redirectToRoute('cms_skillgroup_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de editar la categoría');
            }
        }

        return $this->render('cms/skillgroup/edit.html.twig', [
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
        $group = $this->groupService->get($id);
        if (!$group instanceof SkillGroup) {
            throw new NotFoundHttpException();
        }

        try {
            $this->groupService->remove($group);
            $this->addFlash('app_success', '¡Categoría eliminada!');
        } catch (\Exception $e) {
            $this->addFlash('app_error', 'Hubo un error a la hora de eliminar la categoría');
        }

        return $this->redirectToRoute('cms_skillgroup_index');
    }
}
