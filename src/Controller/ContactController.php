<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    /**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;


    public function __construct(ContactRepository $contactRepository, EntityManagerInterface $em)
    {
        $this->contactRepository=$contactRepository;
        $this->em=$em;
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request): Response
    {
        $contact= new Contact();
        $form=$this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $contact->setCreatedAt(new DateTime());
            $contact->setUser($this->getUser());
            $contact->setStatus(0);

            $this->em->persist($contact);
            $this->em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('contact/contact.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/contact", name="admin_contact")
     */
    public function adminContact(): Response
    {
        $contactsTreated = $this->contactRepository->findBy(['status'=>2]);
        $contactsWaiting = $this->contactRepository->findByStatusField(array(0,1));

        return $this->render('contact/admin_contact.html.twig', [
            'controller_name' => 'ContactController',
            'contactsTreated' => $contactsTreated,
            'contactsWaiting' => $contactsWaiting
        ]);
    }

    /**
     * @Route("/admin/detailed-contact/{id}", name="admin_contact_detailed")
     */
    public function adminContactDetailed(int $id): Response
    {
        $contact = $this->contactRepository->find($id);
        $contact->setStatus(1);
        $this->em->persist($contact);
        $this->em->flush();
        return $this->render('contact/admin_contact_detailed.html.twig', [
            'controller_name' => 'ContactController',
            'contact' => $contact
        ]);
    }

    /**
     * @Route("/admin/treated/{id}", name="admin_contact_treated")
     */
    public function adminContactTreated(int $id): Response
    {
        $contact = $this->contactRepository->find($id);
        $contact->setStatus(2);
        $contact->setTreatedAt(new DateTime());
        $this->em->persist($contact);
        $this->em->flush();
        return $this->redirectToRoute('admin_contact');
    }
}
