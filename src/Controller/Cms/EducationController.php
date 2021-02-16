<?php

namespace App\Controller\Cms;

use App\Entity\Education;
use App\Form\EducationType;
use App\Library\Controller\BaseController;
use App\Service\Cms\EducationService;
use App\Service\ImageService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/educacion", name="education_")
 * @IsGranted("ROLE_ADMIN")
 */
class EducationController extends BaseController
{
    const LIST_LIMIT = 10;

    private $educationService;
    private $imageService;

    public function __construct(EducationService $educationService, ImageService $imageService)
    {
        $this->educationService = $educationService;
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

        $posts = $this->educationService->getAll($filter, $page, $limit, $sort, $dir);
        $paginationData = $this->getPaginationData($request, $posts, $page, $limit);

        return $this->render('cms/education/index.html.twig', array_merge(
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
        $education = new Education();

        $form = $this->createForm(EducationType::class, $education);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/education/new.html.twig', [
                    'form' => $form->createView(),
                    'education' => $education
                ]);
            }

            try {
                $this->educationService->create($education);

                $this->imageService->handleRequest($form, $education);

                $this->addFlash('app_success', '¡Educación creada con éxito!');

                return $this->redirectToRoute('cms_education_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de crear la educación');
            }
        }

        return $this->render('cms/education/new.html.twig', [
            'form' => $form->createView(),
            'education' => $education
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
        $education = $this->educationService->get($id);
        if (!$education instanceof Education) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(EducationType::class, $education);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/education/edit.html.twig', [
                    'form' => $form->createView(),
                    'education' => $education
                ]);
            }

            try {
                $this->educationService->edit($education);

                $this->imageService->handleRequest($form, $education);

                $this->addFlash('app_success', '¡Educación editada con éxito!');

                return $this->redirectToRoute('cms_education_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de editar la educación');
            }
        }

        return $this->render('cms/education/edit.html.twig', [
            'form' => $form->createView(),
            'education' => $education
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
        $education = $this->educationService->get($id);
        if (!$education instanceof Education) {
            throw new NotFoundHttpException();
        }

        try {
            $this->educationService->remove($education);

            $this->imageService->removeEntityImages($education);

            $this->addFlash('app_success', '¡Educación eliminada!');
        } catch (\Exception $e) {
            $this->addFlash('app_error', 'Hubo un error a la hora de eliminar la educación');
        }

        return $this->redirectToRoute('cms_education_index');
    }
}
