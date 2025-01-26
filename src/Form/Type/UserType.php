<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First name',
                'attr' => [
                    'placeholder' => 'John',
                    'class' => 'form-control form-control-lg'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last name',
                'attr' => [
                    'placeholder' => 'Doe',
                    'class' => 'form-control form-control-lg'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email address',
                'attr' => [
                    'placeholder' => 'name@example.com',
                    'class' => 'form-control form-control-lg'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Create password',
                'attr' => [
                    'placeholder' => '••••••••',
                    'class' => 'form-control form-control-lg'
                ]
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control form-control-lg']
            ])
            ->add('register', SubmitType::class, [
                'label' => 'Create account',
                'attr' => ['class' => 'btn btn-primary btn-lg w-100']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
