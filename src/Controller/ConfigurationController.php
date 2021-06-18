<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfigurationController extends AbstractController
{
    #[Route('/configuration', name: 'configuration')]
    public function index(): Response
    {
        return $this->render('configuration/index.html.twig');
    }

    #[Route('/configuration/a-propos', name: 'configuration_about')]
    public function about(): Response
    {
        return $this->render('configuration/about.html.twig');
    }
}
