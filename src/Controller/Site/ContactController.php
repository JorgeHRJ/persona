<?php

namespace App\Controller\Site;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Library\Controller\BaseController;
use App\Library\Mail\ContactReceivedMail;
use App\Library\Mail\RegisteredClientMail;
use App\Service\MailerService;
use App\Service\Site\ContactService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contacto", name="contact_")
 */
class ContactController extends BaseController
{
    private $contactService;
    private $mailerService;

    public function __construct(ContactService $contactService, MailerService $mailerService)
    {
        $this->contactService = $contactService;
        $this->mailerService = $mailerService;
    }

    /**
     * @Route("/", name="form", methods="GET|POST")
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function form(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('site/contact/form.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            try {
                $this->contactService->create($contact);

                $this->addFlash(
                    'app_success',
                    '¡Gracias! Tu mensaje ha sido enviado con éxito. ¡Será atendido lo antes posible!'
                );
            } catch (\Exception $e) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
            }

            try {
                $mail = new ContactReceivedMail();
                $mail->prepare(
                    $this->mailerService->getFrom(),
                    [
                        'name' => $contact->getName(),
                        'email' => $contact->getEmail(),
                        'message' => $contact->getMessage()
                    ]
                );
                $this->mailerService->send($mail);
            } catch (\Exception $e) {
            }
        }

        return $this->render('site/contact/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
