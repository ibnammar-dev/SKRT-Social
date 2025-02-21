<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/posts')]
class PostController extends AbstractApiController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PostRepository $postRepository,
        private ValidatorInterface $validator
    ) {}

    #[Route('', name: 'api_posts_list', methods: ['GET'])]
    public function list(Request $request): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = min(50, max(1, $request->query->getInt('limit', 10)));
        $offset = ($page - 1) * $limit;

        $posts = $this->postRepository->findBy([], ['createdAt' => 'DESC'], $limit, $offset);
        $total = $this->postRepository->count([]);

        $data = array_map(function (Post $post) {
            return [
                'id' => $post->getId(),
                'text' => $post->getText(),
                'imageName' => $post->getImageName(),
                'author' => $post->getUser() ? [
                    'id' => $post->getUser()->getId(),
                    'firstName' => $post->getUser()->getFirstName(),
                    'lastName' => $post->getUser()->getLastName()
                ] : null,
                'createdAt' => $post->getCreatedAt()->format('c')
            ];
        }, $posts);

        return $this->createApiResponse([
            'posts' => $data,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }

    #[Route('/{id}', name: 'api_posts_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->createApiResponse([
            'id' => $post->getId(),
            'text' => $post->getText(),
            'imageName' => $post->getImageName(),
            'author' => $post->getUser() ? [
                'id' => $post->getUser()->getId(),
                'firstName' => $post->getUser()->getFirstName(),
                'lastName' => $post->getUser()->getLastName()
            ] : null,
            'createdAt' => $post->getCreatedAt()->format('c')
        ]);
    }

    #[Route('', name: 'api_posts_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $post = new Post();
        $post->setText($data['text'] ?? '');
        $post->setUser($this->getUser());

        $errors = $this->validator->validate($post);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            return $this->createApiError('Validation failed', Response::HTTP_BAD_REQUEST, $errorMessages);
        }

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $this->createApiResponse([
            'id' => $post->getId(),
            'text' => $post->getText()
        ], 'Post created successfully', Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_posts_update', methods: ['PUT'])]
    public function update(Request $request, Post $post): Response
    {
        // Check if user can edit this post
        $this->denyAccessUnlessGranted('edit', $post);

        $data = json_decode($request->getContent(), true);

        if (isset($data['text'])) {
            $post->setText($data['text']);
        }

        $errors = $this->validator->validate($post);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            return $this->createApiError('Validation failed', Response::HTTP_BAD_REQUEST, $errorMessages);
        }

        $this->entityManager->flush();

        return $this->createApiResponse([
            'id' => $post->getId(),
            'text' => $post->getText()
        ], 'Post updated successfully');
    }

    #[Route('/{id}', name: 'api_posts_delete', methods: ['DELETE'])]
    public function delete(Post $post): Response
    {
        // Check if user can delete this post
        $this->denyAccessUnlessGranted('delete', $post);

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
