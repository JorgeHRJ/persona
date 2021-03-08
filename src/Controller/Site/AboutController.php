<?php

namespace App\Controller\Site;

use App\Library\Controller\BaseController;
use App\Service\Site\CertificationService;
use App\Service\Site\EducationService;
use App\Service\Site\ExperienceService;
use App\Service\Site\ProjectService;
use App\Service\Site\PostService;
use App\Service\Site\SkillGroupService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="about_")
 */
class AboutController extends BaseController
{
    private $projectService;
    private $postService;
    private $experienceService;
    private $educationService;
    private $certificationService;
    private $groupService;
    private $userService;

    public function __construct(
        ProjectService $projectService,
        PostService $postService,
        ExperienceService $experienceService,
        EducationService $educationService,
        CertificationService $certificationService,
        SkillGroupService $groupService,
        UserService $userService
    ) {
        $this->projectService = $projectService;
        $this->postService = $postService;
        $this->experienceService = $experienceService;
        $this->educationService = $educationService;
        $this->certificationService = $certificationService;
        $this->groupService = $groupService;
        $this->userService = $userService;
    }

    /**
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function index(): Response
    {
        $user = $this->userService->getAdmin();

        $profile = $user->getProfile();
        $projects = $this->projectService->get();
        $posts = $this->postService->getLast();
        $experiences = $this->experienceService->get();
        $educations = $this->educationService->get();
        $certifications = $this->certificationService->get();
        $skillGroups = $this->groupService->get();

        return $this->render('site/about/index.html.twig', [
            'user' => $user,
            'profile' => $profile,
            'projects' => $projects,
            'posts' => $posts,
            'experiences' => $experiences,
            'educations' => $educations,
            'certifications' => $certifications,
            'skill_groups' => $skillGroups
        ]);
    }
}
