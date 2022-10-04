<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private CommentaireRepository $commentaireRepository;


    //Demander à symfony d'injecter une instance de ArticleRepository
    // à la création du controlleur (instance de ArticleControlleur
    public function __construct(ArticleRepository $articleRepository , CommentaireRepository $commentaireRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->commentaireRepository = $commentaireRepository;

    }


    #[Route('/articles', name: 'app_articles')]

    // A l'appel de la méthode symfony va créer un objet de la classe ArticleRepossitory
    // et le passer en paramètre de la méthode
    // Mécanisme : INJECTION DE DEPENDANCES
    public function getArticles(PaginatorInterface $paginator , Request $request): Response
    {

        // Récupérer les infos dans la base de données
        // Le controlleur fait appel au Modèle(une classe du modèle)
        // Afin de récupérer la liste des articles
        // $repository = new ArticleRepository();

        //Mise en place de la pagination


        $articles = $paginator->paginate(
            $this->articleRepository->findBy([],["createdAt" => "DESC",]), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );




        return $this->render('article/index.html.twig',[
            "articles" => $articles
        ]);
    }

    #[Route('/articles/{slug}', name: 'app_article_slug')]

    // A l'appel de la méthode symfony va créer un objet de la classe ArticleRepossitory
        // et le passer en paramètre de la méthode
        // Mécanisme : INJECTION DE DEPENDANCES
    public function getArticle($slug): Response
    {

        // Récupérer les infos dans la base de données
        // Le controlleur fait appel au Modèle(une classe du modèle)
        // Afin de récupérer la liste des articles
        // $repository = new ArticleRepository();



        $article = $this->articleRepository->findOneBy(["slug" => $slug] );
        $commentaires = $this->commentaireRepository->findBy(["article_id" => $this->articleRepository->findBy(["slug" => $slug])]);


        return $this->render('article/article.html.twig',[
            "article" => $article,
            "commentaires" => $commentaires
        ]);
    }



    #[Route('/articles/nouveau', name: 'app_articles_nouveau',priority: 1)]

    // A l'appel de la méthode symfony va créer un objet de la classe ArticleRepossitory
        // et le passer en paramètre de la méthode
        // Mécanisme : INJECTION DE DEPENDANCES
    public function insert(SluggerInterface $slugger): Response
    {
        $article = new Article();
        // Création du formulaire
        $formArticle = $this->createForm(ArticleType::class,$article);
        // Appel de le vue twig permettant d'afficher le formulaire

        return $this->renderForm('article/nouveau.html.twig',[
            'formArticle' => $formArticle
            ]
        );




        /*$article->setTitre('Nouvel article 2')
            ->setContenu("Contenu du nouvel article 2")
            ->setSlug($slugger->slug($article->getTitre())->lower())
            ->setCreatedAt(new \DateTime());

        // Propre à symfony 6
        $this->aricleRepository->add($article,true);

        return $this->redirectToRoute("app_articles");
        */
    }
}
