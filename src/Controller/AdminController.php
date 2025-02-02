<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Get total users count
        $totalUsers = $em->getRepository(User::class)->count([]);

        // Get total posts count
        $totalPosts = $em->getRepository(Post::class)->count([]);

        // Get posts in last 24 hours
        $oneDayAgo = new \DateTime('-24 hours');
        $recentPosts = $em->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->where('p.createdAt >= :oneDayAgo')
            ->setParameter('oneDayAgo', $oneDayAgo)
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Get users who joined in last 7 days
        $sevenDaysAgo = new \DateTime('-7 days');
        $newUsers = $em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->where('u.createdAt >= :sevenDaysAgo')
            ->setParameter('sevenDaysAgo', $sevenDaysAgo)
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Get activity data for the last 7 days
        $activityData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = new \DateTime("-$i days");
            $nextDate = new \DateTime("-" . ($i - 1) . " days");

            // Get posts count for this day
            $postsCount = $em->getRepository(Post::class)
                ->createQueryBuilder('p')
                ->select('COUNT(p.id)')
                ->where('p.createdAt >= :start')
                ->andWhere('p.createdAt < :end')
                ->setParameter('start', $date)
                ->setParameter('end', $nextDate)
                ->getQuery()
                ->getSingleScalarResult();

            // Get users count for this day
            $usersCount = $em->getRepository(User::class)
                ->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->where('u.createdAt >= :start')
                ->andWhere('u.createdAt < :end')
                ->setParameter('start', $date)
                ->setParameter('end', $nextDate)
                ->getQuery()
                ->getSingleScalarResult();

            $activityData[] = [
                'date' => $date->format('M d'),
                'posts' => $postsCount,
                'users' => $usersCount
            ];
        }

        return $this->render('admin/dashboard.html.twig', [
            'stats' => [
                'totalUsers' => $totalUsers,
                'totalPosts' => $totalPosts,
                'recentPosts' => $recentPosts,
                'newUsers' => $newUsers,
                'activityData' => $activityData
            ]
        ]);
    }

    #[Route('/users', name: 'admin_users')]
    public function users(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/posts', name: 'admin_posts')]
    public function posts(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $posts = $em->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->leftJoin('p.user', 'u')
            ->addSelect('u')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('admin/posts.html.twig', [
            'posts' => $posts
        ]);
    }
}
