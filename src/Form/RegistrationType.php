<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, [
                'label' => "l' email",
                'attr' => [
                    'placeholder' => 'Entrer votre email'
                ],
            ])
            ->add('codeInterface', null, [
                'label' => "le code interface",
                'attr' => [
                    'placeholder' => 'Entrer le code interface "1234"'
                ],
            ]);
            }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
