<?php

namespace App\Form;

use App\Entity\Person;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('name')
            ->add('phoneNumber')
            ->add('email')
            ->add('nameStreetAddress')
            ->add('additionalAddress')
            ->add('secondAdditionalAddress')
            ->add('zipCode')
            ->add('municipality')
            ->add('isVaccinated')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
