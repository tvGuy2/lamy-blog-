<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private ContactRepository $contactRepository;

    /**
     * @param ContactRepository $contactRepository
     */
    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }


    #[Route('/contact', name: 'app_contact')]
    public function index(EmailService $emailService,Request $request): Response
    {
        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class , $contact);
        $formContact->handleRequest($request);
        if($formContact->isSubmitted() && $formContact->isValid() ){
            $contact->setCreatedAt(new \DateTimeImmutable());
            $this->contactRepository->add($contact,true);
            $emailService->envoyerEmail($contact->getEmail(),"destinataire@test.fr",$contact->getObjet(),
                "email/email.html.twig",["prenom"=>$contact->getPrenom(),
                    "nom" => $contact->getNom(),
                    "contenu" => $contact->getContenu(),
                    "objet"  => $contact->getObjet()]);
            return $this->redirectToRoute("app_articles");
        }

        return $this->renderForm('contact/contact.html.twig', [
            'formContact' => $formContact,

        ]);
    }
}


