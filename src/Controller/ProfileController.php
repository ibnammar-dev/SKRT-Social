<?php

namespace App\Controller;

use App\Form\UserProfileType;
use App\Form\ProfilePictureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Post;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager, ?User $user = null): Response
    {
        if (!$user) {
            $user = $this->getUser();
        }

        // Get user's posts ordered by creation date
        $posts = $entityManager
            ->getRepository(Post::class)
            ->findBy(['user' => $user], ['createdAt' => 'DESC']);

        // Create profile picture form only for current user
        $profilePictureForm = null;
        if ($user && $user === $this->getUser()) {
            $profilePictureForm = $this->createForm(ProfilePictureType::class, $user);
            $profilePictureForm->handleRequest($request);

            if ($profilePictureForm->isSubmitted() && $profilePictureForm->isValid()) {
                $entityManager->flush();
                $this->addFlash('success', 'Profile picture updated successfully!');
                return $this->redirectToRoute('app_profile', ['id' => $user->getId()]);
            }
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'posts' => $posts,
            'profile_picture_form' => $profilePictureForm?->createView()
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated successfully');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
