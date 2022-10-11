<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
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
            TextField::new('titre'),
            TextEditorField::new('contenu'),
            DateTimeField::new('createdAt')->hideOnForm(),
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


}
