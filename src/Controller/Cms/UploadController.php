<?php

namespace App\Controller\Cms;

use App\Library\Controller\BaseController;
use App\Service\Cms\UploadService;
use App\Service\StorageService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/upload", name="upload_", methods={"POST"})
 * @IsGranted("ROLE_USER")
 */
class UploadController extends BaseController
{
    private $validator;
    private $uploadService;

    public function __construct(ValidatorInterface $validator, UploadService $uploadService)
    {
        $this->validator = $validator;
        $this->uploadService = $uploadService;
    }

    /**
     * @Route("/post-image", name="post_image")
     *
     * @param Request $request
     * @return Response
     */
    public function postImage(Request $request): Response
    {
        $uploadedFile = $request->files->get('image');
        if (!$uploadedFile instanceof UploadedFile) {
            return new JsonResponse(['success' => 0], Response::HTTP_BAD_REQUEST);
        }

        try {
            $path = $this->uploadService->upload($uploadedFile, UploadService::POST_IMAGE_ORIGIN);
            return new JsonResponse([
                'success' => 1,
                'file' => ['url' => $path]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => 0], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/image-validate", name="image_validate")
     *
     * @param Request $request
     * @return Response
     */
    public function validate(Request $request): Response
    {
        if (!$request->isXmlHttpRequest()) {
             return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        /** @var UploadedFile $file */
        $file = $request->files->get('file');

        $errors = $this->validator->validate($file, [
            new File([
                'maxSize' => '3M',
                'mimeTypes' => [
                    'image/png',
                    'image/jpg',
                    'image/jpeg',
                ],
                'mimeTypesMessage' => 'La imagen no tiene un formato válido',
                'maxSizeMessage' => 'La imagen no puede superar los 3MB'
            ])
        ]);
        if (count($errors) > 0) {
            return new JsonResponse(
                ['message' => $this->getConstraintErrorMessages($errors)],
                Response::HTTP_BAD_REQUEST
            );
        }

        $data = file_get_contents($file->getRealPath());
        $base64 = sprintf('data:%s;base64,%s', $file->getMimeType(), base64_encode($data));

        return new JsonResponse(['data' => $base64], Response::HTTP_OK);
    }
}
