<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // Générer une URL afin d'accéder à la page d'accueil du CRUD des articles
        $url = $adminUrlGenerator
            ->setController(ArticleCrudController::class)
            ->generateUrl();
        // Rediriger vers cette URL
        return $this->redirect($url);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mon blog');
    }

    public function configureMenuItems(): iterable
    {

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section("Article");
        //Créer un sous menu pour les articles
        yield MenuItem::subMenu("Actions","fas fa-bars")
            ->setSubItems([
                MenuItem::linkToCrud("Lister articles","fas fa-eye",Article::class)
                    ->setDefaultSort(['createdAt' => 'DESC'])
                    ->setAction(Crud::PAGE_INDEX),
               MenuItem::linkToCrud("Ajouter article","fas fa-plus",Article::class)
               ->setAction(Crud::PAGE_NEW)



            ]);

        yield MenuItem::section("Categorie");
        //Créer un sous menu pour les articles
        yield MenuItem::subMenu("Categorie","fas fa-bars")
            ->setSubItems([
                MenuItem::linkToCrud("Lister categories","fas fa-eye",Categorie::class)
                    ->setAction(Crud::PAGE_INDEX),
                MenuItem::linkToCrud("Ajouter categorie","fas fa-plus",Categorie::class)
                    ->setAction(Crud::PAGE_NEW)


            ]);
        yield MenuItem::linkToUrl("accueil","fa fa-home",$this->generateUrl("app_accueil") );

    }
}
