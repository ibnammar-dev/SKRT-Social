<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class UserController extends AbstractController
{
    private $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    #[Route('/user/{id}/delete', name: 'user_delete', methods: ['DELETE'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Only admin can delete users
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Check CSRF token
        $token = $request->headers->get('X-CSRF-TOKEN');
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('delete-item', $token))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        // Can't delete yourself
        if ($user === $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot delete your own account');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/user/{id}/impersonate', name: 'user_impersonate')]
    public function impersonate(User $user): Response
    {
        // Only admin can impersonate users
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Can't impersonate yourself
        if ($user === $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot impersonate yourself');
        }

        // Set the token for impersonation
        $token = sprintf('_switch_user=%s', $user->getEmail());

        // Redirect to homepage with impersonation token
        return $this->redirect('/?' . $token);
    }
}
