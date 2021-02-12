<?php

namespace App\Controller\Cms;

use App\Entity\Certification;
use App\Form\CertificationType;
use App\Library\Controller\BaseController;
use App\Service\Cms\CertificationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/certificaciones", name="certification_")
 * @IsGranted("ROLE_ADMIN")
 */
class CertificationController extends BaseController
{
    const LIST_LIMIT = 10;

    private $certificationService;

    public function __construct(CertificationService $certificationService)
    {
        $this->certificationService = $certificationService;
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

        $posts = $this->certificationService->getAll($filter, $page, $limit, $sort, $dir);
        $paginationData = $this->getPaginationData($request, $posts, $page, $limit);

        return $this->render('cms/certification/index.html.twig', array_merge(
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
        $certification = new Certification();

        $form = $this->createForm(CertificationType::class, $certification);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/certification/new.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                $this->certificationService->create($certification);

                $this->addFlash('app_success', '¡Certificación creada con éxito!');

                return $this->redirectToRoute('cms_certification_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de crear la certificación');
            }
        }

        return $this->render('cms/certification/new.html.twig', [
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
        $certification = $this->certificationService->get($id);
        if (!$certification instanceof Certification) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(CertificationType::class, $certification);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/certification/edit.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                $this->certificationService->edit($certification);

                $this->addFlash('app_success', '¡Certificación editada con éxito!');

                return $this->redirectToRoute('cms_certification_index');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de editar la certificación');
            }
        }

        return $this->render('cms/certification/edit.html.twig', [
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
        $certification = $this->certificationService->get($id);
        if (!$certification instanceof Certification) {
            throw new NotFoundHttpException();
        }

        try {
            $this->certificationService->remove($certification);
            $this->addFlash('app_success', '¡Certificación eliminada!');
        } catch (\Exception $e) {
            $this->addFlash('app_error', 'Hubo un error a la hora de eliminar la certificación');
        }

        return $this->redirectToRoute('cms_certification_index');
    }
}
