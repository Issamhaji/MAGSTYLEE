<?php

namespace App\Controller;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'app_article')]
    public function index(ArticleRepository $repository, Request $request): Response
    {
        $slug = $request->attributes->get('slug');
        $article = $repository->findBy(['slug'=> $slug]);
        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }
}
