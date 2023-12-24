<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nome',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Digite o nome'
                    ])
                ],

            ])
            ->add('description', TextType::class, [
                'label' => 'Descricao',
                'required' => false
            ])
            ->add('value', NumberType::class, [
                'label' => 'Valor',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Insira um valor'
                    ])
                ],
                'attr' => [
                    'min' => 1
                ]
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantidade',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Insira uma quantidade'
                    ])
                    ],
                'attr' => [
                    'min' => 0
                ]
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
            'data_class' => Product::class,
        ]);
    }
}
