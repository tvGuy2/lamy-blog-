<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnDetail(),
            EmailField::new('email'),
            TextField::new('prenom'),
            TextField::new('nom'),
            TextField::new('objet'),
            TextEditorField::new('contenu')->setSortable(false)->setLabel("contenu de l'article")->hideOnIndex(),
            DateTimeField::new('createdAt',"crée le ")->hideOnForm(),

        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Vérifier que $entityInstance est une instance de la classe Article
        if (!$entityInstance instanceof  Article) return;
        $entityInstance->setCreatedAt(new \DateTime());

        // Appel à la méthode héritée afin de persister l'entitée
        parent::persistEntity($entityManager , $entityInstance);
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->setPageTitle(Crud::PAGE_INDEX,"Liste des contacts")
            ->setPaginatorPageSize(10)
            ->setDefaultSort(["createdAt"=>"DESC"]);

        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {

        $actions->add(Crud::PAGE_INDEX,Action::DETAIL);

        $actions->update(Crud::PAGE_INDEX,Action::DETAIL, function (Action $action){
            return $action->setLabel("Détail");
        });

        return $actions ;
    }
}
