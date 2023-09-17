<?php
declare(strict_types=1);

namespace App\Controller;


use App\Entity\Article;
use App\Form\AddArticleType;
use App\Form\SearchType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Voter\EditArticleVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ArticleRepository $repository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $formData = $form->getData();
            $result = $repository->findBy(['title' => $formData['title']]);
            if(!$result){
                $this->addFlash('warning', 'Sorry No Article found with this title');
                return $this->redirectToRoute('app_index');
            }

            return $this->redirectToRoute('app_article',['slug' => $result[0]->getSlug()]);
        }

        $category = $categoryRepository->findBy(['popular'=> true]);
        $populars = $repository->findBy(['category'=> $category]);
        $tredings = $repository->findBy(['trending'=> true]);
        $articles = $repository->findAll();

        return $this->render('index/index.html.twig', [
            'searchForm' => $form->createView(),
            'tredings' => $tredings ,
            'articles' => $articles ,
            'populars' => $populars,
        ]);
    }
    #[Route('/add', name: 'app_add')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function addArticle(ArticleRepository $repository,Request $request): Response
    {
        $user = $this->getUser();
        $article = new Article();
        $article->setUser($user);
        $form = $this->createForm(AddArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // @TODO persist & flush to database
            //the save method is added in Comment repository
            $repository->save($article, true);
            $this->addFlash('success', 'Artcile added successfully');

            return $this->redirectToRoute('app_article',['slug' => $article->getSlug()]);

        }

        return $this->render('index/AddArticle.html.twig',[
            'addArticle' => $form->createView(),
        ]);

    }
    #[Route('/edit/{slug}', name: 'app_edit')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(ArticleRepository $repository,Request $request,Article $article): Response
    {
        // you can enable access control using the code bellow
        /*if($this->isGranted(EditArticleVoter::EDIT, $article) === false ){
            return $this->redirectToRoute('app_index');
        }*/
        $this->denyAccessUnlessGranted(EditArticleVoter::EDIT, $article);

        $form = $this->createForm(AddArticleType::class, $article);
        $form->add('trending');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // @TODO persist & flush to database
            //the save method is added in Comment repository
            $repository->save($article, true);
            $this->addFlash('success', 'Artcile updated successfully');

            return $this->redirectToRoute('app_article',['slug' => $article->getSlug()]);

        }

        return $this->render('index/AddArticle.html.twig',[
            'addArticle' => $form->createView(),
        ]);
    }

}

