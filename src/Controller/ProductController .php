<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    #[Template('product/index.html.twig')]
    public function index(): array
    {
        
        return [
            'category' => '...',
            'promotions' => ['...', '...'],
        ];
    }
}