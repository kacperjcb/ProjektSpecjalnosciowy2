<?php

namespace App\Form;

use App\Entity\ClientInfo;
use App\Entity\CrudRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ClientInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name')
            ->add('Surname')
            ->add('City')
            ->add('PostCode')
            ->add('Address')
            ->add('OrderNumber', HiddenType::class);


    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientInfo::class,
        ]);

    }
}
