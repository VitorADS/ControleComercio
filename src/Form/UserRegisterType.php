<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'E-mail'], [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Informe um e-mail valido'
                    ])
                ]
            ])
            ->add('name', TextType::class, ['label' => 'Nome'], [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Digite seu nome'
                    ])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Senhas nao coincidem',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Digite sua senha'
                    ]),
                    new Length([
                        'min' => 6
                    ])
                ],
                'first_options' => ['label' => 'Senha'],
                'second_options' => ['label' => 'Confirme sua senha']
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Permissoes',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Selecione um'
                    ])
                ],
                'choices' => [
                    'Usuario' => 'ROLE_USER',
                    'Administrador' => 'ROLE_ADMIN'
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Registrar',
                'attr' => [
                    'class' => 'btn-secondary'
                ]
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
