<?php

namespace App\Controller\Cms;

use App\Entity\SkillGroup;
use App\Form\SkillType;
use App\Library\Controller\BaseController;
use App\Service\Cms\SkillGroupService;
use App\Service\Cms\SkillService;
use App\Entity\Skill;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/habilidades", name="skill_")
 * @IsGranted("ROLE_ADMIN")
 */
class SkillController extends BaseController
{
    const LIST_LIMIT = 10;

    private $skillService;
    private $groupService;

    public function __construct(SkillService $skillService, SkillGroupService $groupService)
    {
        $this->skillService = $skillService;
        $this->groupService = $groupService;
    }

    /**
     * @Route("/{groupId}/nuevo", methods="GET|POST", name="new")
     *
     * @param int $groupId
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, int $groupId): Response
    {
        $group = $this->groupService->get($groupId);
        if (!$group instanceof SkillGroup) {
            throw new NotFoundHttpException();
        }

        $skill = new Skill();
        $skill->setGroup($group);

        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/skill/new.html.twig', [
                    'form' => $form->createView(),
                    'group' => $group
                ]);
            }

            try {
                $this->skillService->create($skill);

                $this->addFlash('app_success', '¡Habilidad creada con éxito!');

                return $this->redirectToRoute('cms_skillgroup_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de crear la habilidad');
            }
        }

        return $this->render('cms/skill/new.html.twig', [
            'form' => $form->createView(),
            'group' => $group
        ]);
    }

    /**
     * @Route("/{groupId}/editar/{id}", methods="GET|POST", name="edit")
     *
     * @param Request $request
     * @param int $groupId
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $groupId, int $id): Response
    {
        $group = $this->groupService->get($groupId);
        if (!$group instanceof SkillGroup) {
            throw new NotFoundHttpException();
        }

        $skill = $this->skillService->get($id);
        if (!$skill instanceof Skill) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/skill/edit.html.twig', [
                    'form' => $form->createView(),
                    'group' => $group
                ]);
            }

            try {
                $this->skillService->edit($skill);

                $this->addFlash('app_success', '¡Habilidad editada con éxito!');

                return $this->redirectToRoute('cms_skillgroup_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de editar la habilidad');
            }
        }

        return $this->render('cms/skill/edit.html.twig', [
            'form' => $form->createView(),
            'group' => $group
        ]);
    }

    /**
     * @Route("/{groupId}/eliminar/{id}", name="remove")
     *
     * @param int $groupId
     * @param int $id
     * @return Response
     */
    public function remove(int $groupId, int $id): Response
    {
        $group = $this->groupService->get($groupId);
        if (!$group instanceof SkillGroup) {
            throw new NotFoundHttpException();
        }

        $skill = $this->skillService->get($id);
        if (!$skill instanceof Skill) {
            throw new NotFoundHttpException();
        }

        try {
            $this->skillService->remove($group);
            $this->addFlash('app_success', '¡Habilidad eliminada!');
        } catch (\Exception $e) {
            $this->addFlash('app_error', 'Hubo un error a la hora de eliminar la habilidad');
        }

        return $this->redirectToRoute('cms_skillgroup_index');
    }
}
