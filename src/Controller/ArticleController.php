<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private ArticleRepository $aricleRepository;

    //Demander à symfony d'injecter une instance de ArticleRepository
    // à la création du controlleur (instance de ArticleControlleur
    public function __construct(ArticleRepository $aricleRepository)
    {
        $this->aricleRepository = $aricleRepository;
    }


    #[Route('/articles', name: 'app_articles')]

    // A l'appel de la méthode symfony va créer un objet de la classe ArticleRepossitory
    // et le passer en paramètre de la méthode
    // Mécanisme : INJECTION DE DEPENDANCES
    public function getArticles(): Response
    {

        // Récupérer les infos dans la base de données
        // Le controlleur fait appel au Modèle(une classe du modèle)
        // Afin de récupérer la liste des articles
        // $repository = new ArticleRepository();
        $articles = $this->aricleRepository->findBy([],["createdAt" => "DESC",]);


        return $this->render('article/index.html.twig',[
            "articles" => $articles
        ]);
    }

    #[Route('/article/{id}', name: 'app_article_id')]

    // A l'appel de la méthode symfony va créer un objet de la classe ArticleRepossitory
        // et le passer en paramètre de la méthode
        // Mécanisme : INJECTION DE DEPENDANCES
    public function getArticle($id): Response
    {

        // Récupérer les infos dans la base de données
        // Le controlleur fait appel au Modèle(une classe du modèle)
        // Afin de récupérer la liste des articles
        // $repository = new ArticleRepository();
        $article = $this->aricleRepository->find($id);


        return $this->render('article/article.html.twig',[
            "article" => $article,
        ]);
    }
}
