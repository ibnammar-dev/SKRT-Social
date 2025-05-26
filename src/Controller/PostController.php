<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\Type\PostType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

final class PostController extends AbstractController
{
    private $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    #[Route('/', name: 'post_index')]
    public function index(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $post = new Post();
        $post->setUser($this->getUser());

        $form = $this->createForm(PostType::class, $post);

        // Fetch all posts ordered by creation date (newest first)
        $posts = $entityManager
            ->getRepository(Post::class)
            ->findBy([], ['createdAt' => 'DESC']);

        return $this->render('post/index.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts
        ]);
    }

    #[Route('/post/create', name: 'post_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $post = new Post();
        $post->setUser($this->getUser());

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            // Return the new post HTML using the consistent component
            return $this->render('components/post_card.html.twig', [
                'post' => $post
            ]);
        }

        // If form is not valid, return error response
        $errors = $validator->validate($post);
        return new Response((string) $errors, Response::HTTP_BAD_REQUEST);
    }

    #[Route('/post/createStatic', name: 'create_post')]
    public function createPost(EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $post = new Post();
        $post->setText(10);

        $entityManager->persist($post);
        $entityManager->flush();

        $errors = $validator->validate($post);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        return new Response('Saved new post with id ' . $post->getId());
    }

    #[Route('/post/{id}', name: 'post_show')]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $post = $entityManager->getRepository(Post::class)->find($id);

        if (!$post) {
            return new Response('No post found for id ' . $id);
        }

        return new Response('Check out this great post: ' . $post->getText());
    }

    #[Route('/post/edit/{id}', name: 'post_edit')]
    public function update(EntityManagerInterface $entityManager, int $id): Response
    {
        $post = $entityManager->getRepository(Post::class)->find($id);

        if (!$post) {
            return new Response('No post found for id ' . $id);
        }

        $post->setText('New post text!');
        $entityManager->flush();

        return $this->redirectToRoute('post_show', [
            'id' => $post->getId()
        ]);
    }

    #[Route('/post/delete/{id}', name: 'post_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $post = $entityManager->getRepository(Post::class)->find($id);
        $entityManager->remove($post);
        $entityManager->flush();
        return new Response('Post deleted');
    }

    #[Route('/post/{id}/edit-form', name: 'post_edit_form')]
    public function editForm(Post $post): Response
    {
        $this->denyAccessUnlessGranted('edit', $post);
        return $this->render('post/_edit_form.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/post/{id}/content', name: 'post_content')]
    public function content(Post $post): Response
    {
        return $this->render('post/_content.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/post/{id}/edit', name: 'post_edit', methods: ['POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('edit', $post);

        $token = $request->headers->get('X-CSRF-TOKEN');
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('delete-item', $token))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $post->setText($request->request->get('text'));

        // Handle image upload if present
        if ($request->files->has('image')) {
            $post->setImageFile($request->files->get('image'));
        }

        $entityManager->flush();

        // Return just the content part
        return $this->render('post/_content.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/post/{id}/delete', name: 'post_delete', methods: ['DELETE'])]
    public function deletePost(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('delete', $post);

        $token = $request->headers->get('X-CSRF-TOKEN');
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('delete-item', $token))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $entityManager->remove($post);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/post/{id}/remove-image', name: 'post_remove_image', methods: ['POST'])]
    public function removeImage(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('edit', $post);

        $token = $request->headers->get('X-CSRF-TOKEN');
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('delete-item', $token))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        // Remove the image
        $post->setImageName(null);
        $post->setImageFile(null);
        $entityManager->flush();

        return $this->render('post/_edit_form.html.twig', [
            'post' => $post
        ]);
    }
}
