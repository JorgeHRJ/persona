<?php

namespace App\Controller\Cms;

use App\Form\UserProfileType;
use App\Library\Controller\BaseController;
use App\Service\ImageService;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/usuario", name="user_")
 * @IsGranted("ROLE_USER")
 */
class UserController extends BaseController
{
    private $userService;
    private $imageService;

    public function __construct(UserService $userService, ImageService $imageService)
    {
        $this->userService = $userService;
        $this->imageService = $imageService;
    }

    /**
     * @Route("/cuenta", methods="GET|POST", name="account")
     *
     * @param Request $request
     * @return Response
     */
    public function account(Request $request): Response
    {
        $user = $this->getUserInstance();

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/user/account.html.twig', [
                    'form' => $form->createView(),
                    'user' => $user
                ]);
            }

            try {
                $this->userService->edit($user);

                $this->imageService->handleRequest($form, $user);

                $this->addFlash('app_success', '¡Cuenta editada con éxito!');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de editar tu cuenta');
            }
        }

        return $this->render('cms/user/account.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
