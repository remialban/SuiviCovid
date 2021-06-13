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
    #[Route('/personnes/ajouter', name: 'person_add')]
    #[Route('/personnes/{id}/modifier', name: 'person_edit')]
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

    #[Route('/personnes/chercher', name: 'person_list')]
    public function list(PersonRepository $personRepository, PersonSearch $personSearch, Request $request)
    {
        $response = $personSearch->searchPerson($request->get("q", "*"), $request->get("p", 1), $request->get("number", 20));
        $persons = $response['content'];
        return $this->render('person/list.html.twig', [
            'persons' => $persons,
            'pageNumber' => $response['page_numbers'],
            'currentPage' => $response['page'],
            "resultsNumber" => $response['found'],
            "query" => $request->get("q", "*")
        ]);
    }

    #[Route("/personnes/{id}", name: 'person_show')]
    public function show(Person $person, Request $request)
    {
        return $this->render('person/show.html.twig', [
            "person" => $person
        ]);
    }

    #[Route("/personnes/{id}/supprimer", name: 'person_delete')]
    public function delete(Person $person, PersonSearch $personSearch)
    {
        $manager = $this->getDoctrine()->getManager();

        $personSearch->removePerson($person);
        
        return $this->redirectToRoute("home");
    }
}