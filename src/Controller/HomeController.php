<?php

namespace App\Controller;

use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PersonRepository $personRepository): Response
    {
        $elements = $personRepository->getByDate(new \DateTime);

        return $this->render('home/index.html.twig', [
            "person_this_day_number" => sizeof($elements)
        ]);
    }
}
