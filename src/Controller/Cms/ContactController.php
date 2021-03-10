<?php

namespace App\Controller\Cms;

use App\Entity\Contact;
use App\Library\Controller\BaseController;
use App\Service\Cms\ContactService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contactos", name="contact_")
 */
class ContactController extends BaseController
{
    const LIST_LIMIT = 10;

    private $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
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

        $posts = $this->contactService->getAll($filter, $page, $limit, $sort, $dir);
        $paginationData = $this->getPaginationData($request, $posts, $page, $limit);

        return $this->render('cms/contact/index.html.twig', array_merge(
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
     * @Route("/{id}/detalle", name="show")
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $contact = $this->contactService->get($id);
        if (!$contact instanceof Contact) {
            throw new NotFoundHttpException();
        }

        $this->contactService->updateStatus($contact, Contact::STATUS_READ);

        return $this->render('cms/contact/show.html.twig', [
            'contact' => $contact
        ]);
    }

    /**
     * @Route("/{id}/marcar/{status}", name="mark")
     *
     * @param int $id
     * @param int $status
     * @return Response
     */
    public function mark(int $id, int $status): Response
    {
        $contact = $this->contactService->get($id);
        if (!$contact instanceof Contact) {
            throw new NotFoundHttpException();
        }

        $this->contactService->updateStatus($contact, $status);

        return $this->redirectToRoute('cms_contact_index');
    }
}
