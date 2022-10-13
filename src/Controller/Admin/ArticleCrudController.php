<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\OrderBy;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleCrudController extends AbstractCrudController
{
    private SluggerInterface $slugger;

    // Injection du slugger au niveau du constructeur
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre',"titre article"),
            TextEditorField::new('contenu')->setSortable(false)->setLabel("contenu de l'article")->hideOnIndex(),
            AssociationField::new('categorie')->setRequired(false),
            DateTimeField::new('createdAt',"crée le ")->hideOnForm(),
            TextField::new('slug')->hideOnForm(),
            BooleanField::new('isPublie')



        ];
    }

    // Redéfinir la méthode persistEntity qui va être appelé lors de la création de l'article en base de données
    // Générer l'ordre insert
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
       // Vérifier que $entityInstance est une instance de la classe Article
        if (!$entityInstance instanceof  Article) return;
        $entityInstance->setCreatedAt(new \DateTime())
            ->setSlug($this->slugger->slug($entityInstance->getTitre())->lower());


        // Appel à la méthode héritée afin de persister l'entitée
        parent::persistEntity($entityManager , $entityInstance);
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->setPageTitle(Crud::PAGE_INDEX,"Liste des articles")
            ->setPageTitle(Crud::PAGE_NEW,"Création d'un article")
            ->setPageTitle(Crud::PAGE_EDIT,"Modifier un article")
            ->setPaginatorPageSize(10)
        ->setDefaultSort(["createdAt"=>"DESC"]);

        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX,Action::NEW, function (Action $action){
            return $action->setLabel("Ajouter article")
                            ->setIcon("fa fa-plus");
        });
        $actions->update(Crud::PAGE_NEW,Action::SAVE_AND_RETURN, function (Action $action){
            return $action->setLabel("Valider")
                ->setIcon("fa fa-plus");
        });
        $actions->remove(Crud::PAGE_NEW,Action::SAVE_AND_ADD_ANOTHER);

        $actions->add(Crud::PAGE_INDEX,Action::DETAIL);

        $actions->update(Crud::PAGE_INDEX,Action::DETAIL, function (Action $action){
            return $action->setLabel("Détail");
        });




        return $actions ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters->add("titre")
            ->add("createdAt");
        return $filters;
    }


}
