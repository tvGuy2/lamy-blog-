<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{

    private CategorieRepository $categorieRepository;
    private ArticleRepository $articleRepository;

    //Demander à symfony d'injecter une instance de ArticleRepository
    // à la création du controlleur (instance de ArticleControlleur
    public function __construct(CategorieRepository $categorieRepository, ArticleRepository $articleRepository)
    {
        $this->categorieRepository = $categorieRepository;
        $this->articleRepository = $articleRepository;
    }

    #[Route('/categories', name: 'app_categories')]
    public function index(): Response
    {
        $categories = $this->categorieRepository->findBy([],["titre" => "ASC"]);

        return $this->render('categorie/index.html.twig', [
            "categories" => $categories
        ]);

    }

    #[Route('/categorie/{slug}', name: 'app_categorie_slug')]


    public function getArticle( $slug): Response
    {
        $articles= $this->articleRepository->findBy(["categorie" => $this->categorieRepository ->findOneBy(["slug" => $slug])] );
        $categorie = $this->categorieRepository ->findOneBy(["slug" => $slug]);



        return $this->render('categorie/categorie.html.twig',[
            "articles" => $articles,
            "categorie" => $categorie
        ]);
    }
}
