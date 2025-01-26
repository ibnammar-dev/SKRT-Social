<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_username', EmailType::class, [
                'label' => 'Email address',
                'attr' => [
                    'placeholder' => 'Enter your email',
                    'autocomplete' => 'email',
                    'class' => 'form-control form-control-lg'
                ]
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'Password',
                'attr' => [
                    'placeholder' => '••••••••',
                    'autocomplete' => 'current-password',
                    'class' => 'form-control form-control-lg'
                ]
            ])
            ->add('login', SubmitType::class, [
                'label' => 'Sign in to your account',
                'attr' => ['class' => 'btn btn-primary btn-lg w-100']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id'   => 'authenticate'
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
