<?php

namespace App\Form;

use App\DTO\PaymentDTO;
use App\Entity\PaymentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('total', NumberType::class, [
                'label' => 'Valor',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Insira um valor'
                    ])
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Descricao',
                'required' => false
            ])
            ->add('paymentType', EntityType::class, [
                'class' => PaymentType::class,
                'choice_label' => function (PaymentType $paymentType): string {
                    return $paymentType;
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Adicionar',
                'attr' => [
                    'class' => 'btn-secondary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentDTO::class,
        ]);
    }
}
