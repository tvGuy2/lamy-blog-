<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]

    public function getArticles( ArticleRepository $repository): Response
    {

        // Récupérer les infos dans la base de données
        // Le controlleur fait appel au Modèle(une classe du modèle)
        // Afin de récupérer la liste des articles
        // $repository = new ArticleRepository();
        $articles = $repository->findBy([],["createdAt" => "DESC",],10);


        return $this->render('accueil/index.html.twig',[
            "articles" => $articles
        ]);
    }
}
