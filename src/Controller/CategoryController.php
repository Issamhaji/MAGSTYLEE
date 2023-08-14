<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'app_category')]
    public function index(ArticleRepository $repository, Category $category): Response
    {
        $articles = $repository->findBy(['category'=>$category]);
        return $this->render('category/index.html.twig', [
            'articles' => $articles ,
            'category' => $category,
        ]);
    }

    public function category(CategoryRepository $repository): Response
    {
        $categories = $repository->findAll();

        return $this->render('category/_category_items.html.twig', [
            'categories' => $categories,
        ]);
    }
}
