<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\AddPersonType;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route('/ajouter', name: 'person_add')]
    public function index(Person $person = null, Request $request): Response
    {
        if ($person == null)
        {
            $person = new Person();
        }
        $form = $this->createForm(AddPersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $person->setAddedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute("home");
        }

        return $this->render('person/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/liste', name: 'person_list')]
    public function list(PersonRepository $personRepository)
    {
        return $this->render('person/list.html.twig', [
            'persons' => $personRepository->findAll()
        ]);
    }
}