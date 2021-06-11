<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\AddPersonType;
use App\Repository\PersonRepository;
use App\Utils\PersonSearch;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route('/personne/ajouter', name: 'person_add')]
    #[Route('/personne/{id}/modifier', name: 'person_edit')]
    public function index(Person $person = null, Request $request, PersonSearch $personSearch): Response
    {
        $add = false;
        if ($person == null)
        {
            $person = new Person();
            $add = true;
        }
        $form = $this->createForm(AddPersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $person->setAddedAt(new \DateTime());
            $personSearch->updatePerson($person);
            return $this->redirectToRoute("home");
        }

        return $this->render('person/add.html.twig', [
            'form' => $form->createView(),
            'add' => $add
        ]);
    }

    #[Route('/personne/liste', name: 'person_list')]
    public function list(PersonRepository $personRepository)
    {
        return $this->render('person/list.html.twig', [
            'persons' => $personRepository->findAll()
        ]);
    }

    #[Route("/personne/{id}", name: 'person_show')]
    public function show(Person $person, Request $request)
    {
        return $this->render('person/show.html.twig', [
            "person" => $person
        ]);
    }

    #[Route("/personne/{id}/supprimer", name: 'person_delete')]
    public function delete(Person $person)
    {
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($person);
        $manager->flush();
        
        return $this->redirectToRoute("home");
    }
}