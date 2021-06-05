<?php

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PersonFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create("FR_fr");
        
        for ($i=0; $i < 1000; $i++) { 
            $person = new Person();
            $person->setFirstName($faker->firstName);
            $person->setName($faker->name);
            $person->setIsVaccinated($faker->boolean(25));
            $person->setAddedAt($faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now', $timezone = null));

            if ($faker->boolean(50))
            {
                $person->setPhoneNumber(intval($faker->phoneNumber));
            }

            if ($faker->boolean(50))
            {
                $person->setEmail($faker->email);
            }

            if ($faker->boolean(75))
            {
                $person->setNameStreetAddress($faker->streetAddress);
                $person->setZipCode(intval($faker->postcode));
                $person->setMunicipality($faker->city);

                if ($faker->boolean(10))
                {
                    $person->setAdditionalAddress($faker->secondaryAddress);
                    $person->setSecondAdditionalAddress($faker->buildingNumber);
                }
            }
            $manager->persist($person);
        }

        $manager->flush();
    }
}
