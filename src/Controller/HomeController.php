<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    private $repoArticle;
    private $repoCategory;
    
    public function __construct(ArticleRepository $repoArticle, CategoryRepository $repoCategory)
    {
        $this->repoArticle = $repoArticle;
        $this->repoCategory = $repoCategory;
    }
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        $categories = $this->repoCategory->findAll();
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $this->repoArticle->findAll();
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }

     /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Article $article): Response
    {
        
        if(!$article){
            return $this->redirectToRoute('home');
        }
        return $this->render('show/index.html.twig', [
            'article' => $article,
        ]);
    }

     /**
     * @Route("/showArticle/{id}", name="show_article")
     */
    public function showArticle(?Category $category): Response
    {
        if($category){
            $articles = $category->getArticles()->getValues();
        }else{ //si on a pas d'articles
            return $this->redirectToRoute('home');
        }
        $categories = $this->repoCategory->findAll();
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }
}
