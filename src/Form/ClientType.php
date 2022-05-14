<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('cpf', TextType::class)
            ->add('date', DateTimeType::class)
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class)
            ->add('edit', SubmitType::class)
        ;
    }

}
