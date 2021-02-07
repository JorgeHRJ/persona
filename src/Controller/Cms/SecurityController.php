<?php

namespace App\Controller\Cms;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/", name="security_")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() instanceof User) {
            return $this->redirectToRoute('cms_dashboard_index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('cms/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}
