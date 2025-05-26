<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/media')]
class MediaController extends AbstractApiController
{
    #[Route('/images/{type}/{imageName}', name: 'api_media_image', methods: ['GET'])]
    public function getImage(string $type, string $imageName): Response
    {
        // Map type to folder
        $folderMap = [
            'posts' => 'images/posts',
            'profiles' => 'images/profiles'
        ];

        if (!isset($folderMap[$type])) {
            return $this->createApiError('Invalid image type. Must be one of: ' . implode(', ', array_keys($folderMap)), Response::HTTP_BAD_REQUEST);
        }

        $imagePath = $this->getParameter('kernel.project_dir') . '/public/' . $folderMap[$type] . '/' . $imageName;

        if (!file_exists($imagePath)) {
            return $this->createApiError('Image not found', Response::HTTP_NOT_FOUND);
        }

        return new BinaryFileResponse($imagePath);
    }
}
