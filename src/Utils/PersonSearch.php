<?php

namespace App\Utils;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Gère la communication avec TypeSense
 * 
 * @author Rémi ALBAN <remialbanperso@gmail.com>
 */
class PersonSearch
{
    /**
     * Contient la clé API qui sert à communiquer avec TypeSense
     * @var string
     */
    const API_KEY = "Hu52dwsas2AdxdE";

    /**
     * Url de TypeSense
     * @var string
     */
    const API_URL = "http://localhost:8108";

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * Permet de communiquer par HTTP avec TypeSense
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var PersonRepository
     */
    private $personRepository;

    public function __construct(EntityManagerInterface $em, HttpClientInterface $client, PersonRepository $personRepository)
    {
        $this->em = $em;
        $this->client = $client;
        $this->personRepository = $personRepository;
    }

        
    /**
     * Mettre à jour une personne dans la base de donnée et dans TypeSense
     *
     * @param  mixed $person Personne à mettre à jour
     * @return void
     */
    public function updatePerson(Person $person)
    {
        if (!$person->getId())
        {
            $this->em->persist($person);
            $this->em->flush();
            $this->em->refresh($person);
        }        

        $json = [
            "id_entity" => $person->getId(),
            "firstName" => $person->getFirstName(),
            "name" => $person->getName(),
            "isVaccinated" => $person->getIsVaccinated()
        ];

        if ($person->getPhoneNumber())
        {
            $json["phoneNumber"] = $person->getPhoneNumber();
        }

        if ($person->getEmail())
        {
            $json["email"] = $person->getEmail();
        }

        if ($person->getNameStreetAddress())
        {
            $json["nameStreetAddress"] = $person->getNameStreetAddress();
        }

        if ($person->getAdditionalAddress())
        {
            $json["additionalAddress"] = $person->getAdditionalAddress();
        }

        if ($person->getSecondAdditionalAddress())
        {
            $json["secondAdditionalAddress"] = $person->getSecondAdditionalAddress();
        }

        if ($person->getZipCode())
        {
            $json["zipCode"] = $person->getZipCode();
        }

        if ($person->getMunicipality())
        {
            $json["municipality"] = $person->getMunicipality();
        }

        if ($person->getIdTypeSense())
        {
            $response = $this->run("PATCH", "collections/persons/documents/" . $person->getIdTypeSense(), $json);
        } else
        {
            $response = $this->run("POST", "collections/persons/documents", $json);
            $person->setIdTypeSense($response['id']);    
        }
        $this->em->persist($person);
        $this->em->flush();
    }
    
    /**
     * Permet d'exécuter une requête TypeSense
     *
     * @param  string $method Méthode de la requête (ex: POST, GET, PATCH, DELETE)
     * @param  string $url Url de l'api à appeler (ex: collections/persons/documents/search)
     * @param  array $data Données à transmettre 
     * @return array
     */
    private function run(string $method, string $url, array $data = [])
    {
        return $this->client->request($method, $this::API_URL . "/" . $url, [
            "headers" => [
                "x-typesense-api-key" => $this::API_KEY
            ],
            "json" => $data
        ])->toArray();
    }

        
    /**
     * Chercher une personne
     *
     * @param string $query Chaîne de texte à rechercher
     * @param int $page Page à rechercher
     * @param int $number_per_page Nombre de résultats par page
     * 
     * @return array
     */
    public function searchPerson(string $query, int $page = 1, int $number_per_page = 10)
    {
        $responseData = $this->run("GET", "collections/persons/documents/search?query_by=firstName, name, municipality&q=" . $query . "&per_page=$number_per_page&page=$page", []);
        $persons = [];
        foreach ($responseData['hits'] as $item) {
            $persons[] = $this->personRepository->find($item['document']['id_entity']);
        }
        
        $response = [
            "content" => $persons,
            "number_per_page" => $number_per_page,
            "found" => $responseData['found'],
            "page" => $page,
            "page_numbers" => $responseData['found'] % $number_per_page == 0 ? $responseData['found'] / $number_per_page : floor($responseData['found'] / $number_per_page) + 1
        ];
        
        return $response;
    }

    /**
     * Supprime une personne
     * 
     * @param Person $person Personne à supprimer
     */
    public function removePerson(Person $person)
    {
        $this->run("DELETE", "collections/persons/documents/" . $person->getIdTypeSense());
        $this->em->remove($person);
        $this->em->flush();
    }
}