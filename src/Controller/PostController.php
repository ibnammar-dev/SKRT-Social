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

final class PostController extends AbstractController
{
    #[Route('/', name: 'post_index')]
    public function index(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();
            $errors = $validator->validate($post);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            }
            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        // Fetch all posts
        $posts = $entityManager->getRepository(Post::class)->findAll();

        return $this->render('post/index.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts
        ]);
    }


    #[Route('/post/createStatic', name: 'create_post')]
    public function createPost(EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $post = new Post();
        //$post->setText('At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium ');
        $post->setText(10);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($post);

        // actually executes the queries (i.e. the INSERT query)
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

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
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
}
