<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'app_article')]
    public function index(ArticleRepository $repository, Request $request ,CommentRepository $commentRepository): Response
    {
        $slug = $request->attributes->get('slug');
        $article = $repository->findOneBy(['slug'=> $slug]);
        //form comment
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // @TODO persist & flush to database
            $comment->setArticle($article);
            //the save method is added in Comment repository
            $commentRepository->save($comment, true);
            $this->addFlash('success', 'Product saved successfully');

            return $this->redirectToRoute('app_article',['slug' => $slug]);

        }

        $comments = $commentRepository->findBy(['article'=>$article]);
        return $this->render('article/index.html.twig', [
            'item' => $article,
            'addForm' => $form->createView(),
            'comments' => $comments,
        ]);
    }


}
