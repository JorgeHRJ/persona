<?php

namespace App\Controller\Cms;

use App\Entity\Experience;
use App\Form\ExperienceType;
use App\Library\Controller\BaseController;
use App\Service\Cms\ExperienceService;
use App\Service\ImageService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/experiencia", name="experience_")
 * @IsGranted("ROLE_ADMIN")
 */
class ExperienceController extends BaseController
{
    const LIST_LIMIT = 10;

    private $experienceService;
    private $imageService;

    public function __construct(ExperienceService $experienceService, ImageService $imageService)
    {
        $this->experienceService = $experienceService;
        $this->imageService = $imageService;
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

        $posts = $this->experienceService->getAll($filter, $page, $limit, $sort, $dir);
        $paginationData = $this->getPaginationData($request, $posts, $page, $limit);

        return $this->render('cms/experience/index.html.twig', array_merge(
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
        $experience = new Experience();

        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/experience/new.html.twig', [
                    'form' => $form->createView(),
                    'experience' => $experience
                ]);
            }

            try {
                $this->experienceService->create($experience);

                $this->imageService->handleRequest($form, $experience);

                $this->addFlash('app_success', '¡Experiencia creada con éxito!');

                return $this->redirectToRoute('cms_experience_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de crear la experiencia');
            }
        }

        return $this->render('cms/experience/new.html.twig', [
            'form' => $form->createView(),
            'experience' => $experience
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
        $experience = $this->experienceService->get($id);
        if (!$experience instanceof Experience) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/experience/edit.html.twig', [
                    'form' => $form->createView(),
                    'experience' => $experience
                ]);
            }

            try {
                $this->experienceService->edit($experience);

                $this->imageService->handleRequest($form, $experience);

                $this->addFlash('app_success', '¡Experiencia editada con éxito!');

                return $this->redirectToRoute('cms_experience_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de editar la experiencia');
            }
        }

        return $this->render('cms/experience/edit.html.twig', [
            'form' => $form->createView(),
            'experience' => $experience
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
        $experience = $this->experienceService->get($id);
        if (!$experience instanceof Experience) {
            throw new NotFoundHttpException();
        }

        try {
            $this->experienceService->remove($experience);

            $this->imageService->removeEntityImages($experience);

            $this->addFlash('app_success', '¡Experiencia eliminada!');
        } catch (\Exception $e) {
            $this->addFlash('app_error', 'Hubo un error a la hora de eliminar la experiencia');
        }

        return $this->redirectToRoute('cms_experience_index');
    }
}
