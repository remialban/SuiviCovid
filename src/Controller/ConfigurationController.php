<?php

namespace App\Controller;

use App\Form\SMTPType;
use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfigurationController extends AbstractController
{
    #[Route('/configuration', name: 'configuration')]
    public function index(Request $request, PropertyRepository $propertyRepository, EntityManagerInterface $em): Response
    {
        $server = $propertyRepository->findOneBy(['name' => 'smtp_server']);
        if (!$server)
        {
            $server = new Property();
            $server->setName("smtp_server");
        }
        $port = $propertyRepository->findOneBy(['name' => 'smtp_port']);
        if (!$port)
        {
            $port = new Property();
            $port->setName("smtp_port");
        }
        $email = $propertyRepository->findOneBy(['name' => 'smtp_email']);
        if (!$email)
        {
            $email = new Property();
            $email->setName("smtp_email");
        }
        $password = $propertyRepository->findOneBy(['name' => 'smtp_password']);
        if (!$password)
        {
            $password = new Property();
            $password->setName("smtp_password");
        }
        $encryption = $propertyRepository->findOneBy(['name' => 'smtp_encryption']);
        if (!$encryption)
        {
            $encryption = new Property();
            $encryption->setName("smtp_encryption");
        }
        $message = $propertyRepository->findOneBy(['name' => 'smtp_message']);
        if (!$message)
        {
            $message = new Property();
            $message->setName("smtp_message");
            $message->setValue("Bonjour,<br/><br/>Une personne est venu dans notre établissement qui a été testé positive. Il est venu le même jour que vous êtes venues. Faites-vous tester.<br/><br/>Ensemble, respectons les gestes barrières");
        }

        $configuration = [
            "smtp_server" => $server->getValue(),
            "smtp_port" => $port->getValue(),
            "smtp_email" => $email->getValue(),
            "smtp_password" => $encryption->getValue(),
            "smtp_encryption" => $encryption->getValue(),
            "smtp_message" => $message->getValue()
        ];

        $form = $this->createForm(SMTPType::class, $configuration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            
            $server->setValue($task['smtp_server']);
            $port->setValue($task['smtp_port']);
            $encryption->setValue($task['smtp_encryption']);
            $email->setValue($task['smtp_email']);
            $password->setValue($task['smtp_password']);

            $em->persist($server);
            $em->persist($port);
            $em->persist($encryption);
            $em->persist($email);
            $em->persist($password);
            $em->flush();
        }

        return $this->render('configuration/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/configuration/a-propos', name: 'configuration_about')]
    public function about(): Response
    {
        return $this->render('configuration/about.html.twig');
    }
}
