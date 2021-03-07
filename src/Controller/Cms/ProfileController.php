<?php

namespace App\Controller\Cms;

use App\Entity\Profile;
use App\Form\ProfileType;
use App\Library\Controller\BaseController;
use App\Service\Cms\ProfileService;
use App\Service\ImageService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/perfil", name="profile_")
 * @IsGranted("ROLE_ADMIN")
 */
class ProfileController extends BaseController
{
    private $profileService;
    private $imageService;

    public function __construct(ProfileService $profileService, ImageService $imageService)
    {
        $this->profileService = $profileService;
        $this->imageService = $imageService;
    }

    /**
     * @Route("/", methods="GET|POST", name="index")
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $user = $this->getUserInstance();
        $profile = $user->getProfile() instanceof Profile ? $user->getProfile() : new Profile();

        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('app_error', $this->getFormErrorMessagesList($form, true));
                return $this->render('cms/profile/index.html.twig', [
                    'form' => $form->createView(),
                    'profile' => $profile
                ]);
            }

            try {
                if ($profile->getId() === null) {
                    $profile->setUser($user);
                    $this->profileService->create($profile);
                }

                if ($profile->getId() !== null) {
                    $this->profileService->edit($profile);
                }

                $this->imageService->handleRequest($form, $profile);

                $this->addFlash('app_success', '¡Perfil público editado con éxito!');
            } catch (\Exception $e) {
                $this->addFlash('app_error', 'Hubo un problema a la hora de editar tu perfil público');
            }
        }

        return $this->render('cms/profile/index.html.twig', [
            'form' => $form->createView(),
            'profile' => $profile
        ]);
    }
}
