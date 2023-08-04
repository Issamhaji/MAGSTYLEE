<?php

namespace App\Controller;


use App\Repository\ArticleRepository;
use App\Service\ArticleInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ArticleRepository $repository): Response
    {
        $tredings = $repository->findBy(['trending'=> true]);
        $articles = $repository->findAll();
        return $this->render('index/index.html.twig', [
            'tredings' => $tredings ,
            'articles' => $articles ,
        ]);
    }
}
